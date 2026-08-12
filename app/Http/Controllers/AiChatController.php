<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'array'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'error' => 'Ups! API Key Gemini (GEMINI_API_KEY) belum dipasang di server (.env). Lapor ke admin ya!'
            ], 500);
        }

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        // System Instruction (GameVault context)
        $systemInstruction = "Kamu adalah 'VaultBot', asisten AI resmi dari GameVault. 
GameVault adalah platform pembelian game digital PC dengan lisensi resmi, pengiriman instan, dan jaminan 14 hari.
Fitur di GameVault:
1. Bisa dibeli lewat Midtrans (QRIS, E-Wallet, Virtual Account).
2. Memiliki fitur Library: game yang sukses dibeli otomatis masuk ke Library dan bisa dimainkan kapan saja.
3. Memiliki fitur Bundle DLC otomatis dengan diskon khusus.
4. Memiliki fitur Wishlist, Cart, Checkout, dan Review Game.
5. Refund bisa dilakukan maksimal 14 hari dari tanggal pembelian via menu Profile.
6. Admin mengurus penambahan game, DLC, dan manajemen harga lewat dashboard admin.
Gaya bicaramu: Ramah, santai, dan bergaya seperti seorang gamer (gunakan bahasa Indonesia sehari-hari, jangan kaku, tapi tetap solutif). Gunakan emoji seperlunya. Kalau ditanya di luar konteks game/toko, arahkan kembali fokusnya ke GameVault.";

        // Format history for Gemini API
        $contents = [];
        foreach ($history as $msg) {
            // pastikan format valid
            if (isset($msg['role']) && isset($msg['text'])) {
                $role = $msg['role'] === 'user' ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $msg['text']]]
                ];
            }
        }

        // Add the current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 600,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, VaultBot lagi nge-lag koneksinya nih. Coba lagi sebentar lagi ya!";
                
                // Parse markdown partially to HTML for basic formatting (bold, newlines)
                // We'll let the frontend do the basic parse or do simple replacement here.
                return response()->json([
                    'reply' => $reply
                ]);
            }

            // Handle rate limits / high demand gracefully
            $status = $response->status();
            $friendlyMessage = "Waduh, koneksi ke otak AI VaultBot lagi terputus nih (Error: $status). Coba lagi bentar ya!";
            
            if ($status === 503 || $status === 429) {
                $friendlyMessage = "Wah, VaultBot lagi kebanjiran chat dari banyak gamer nih (Server penuh). Santai dulu, coba chat lagi dalam beberapa menit ya! \u{1F64F}";
            }

            return response()->json([
                'error' => $friendlyMessage
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan internal: ' . $e->getMessage()
            ], 500);
        }
    }
}
