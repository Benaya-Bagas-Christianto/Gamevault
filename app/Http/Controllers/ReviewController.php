<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Game $game)
    {
        $user = Auth::user();

        // Must own the game
        abort_unless($user->ownedGames()->where('games.id', $game->id)->exists(), 403, 'Kamu harus membeli game ini untuk memberi ulasan.');

        // Only one review per game
        if ($user->reviews()->where('game_id', $game->id)->exists()) {
            return back()->with('error', 'Kamu sudah memberi ulasan untuk game ini.');
        }

        $request->validate([
            'rating'      => 'required|integer|between:1,5',
            'body'        => 'nullable|string|max:1000',
            'media'       => 'nullable|file|mimes:jpg,jpeg,png,mp4,webm|max:51200', // 50MB max
            'media_type'  => 'nullable|in:image,video',
            'video_start' => 'nullable|integer|min:0',
            'video_end'   => 'nullable|integer|min:0',
        ]);

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('reviews', 'public');
        } elseif ($request->filled('media_base64')) {
            try {
                $image_parts = explode(";base64,", $request->media_base64);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1] ?? 'jpeg';
                $image_base64 = base64_decode($image_parts[1]);
                $mediaPath = 'reviews/' . uniqid() . '.' . $image_type;
                Storage::disk('public')->put($mediaPath, $image_base64);
            } catch (\Exception $e) {}
        }

        Review::create([
            'user_id'     => $user->id,
            'game_id'     => $game->id,
            'rating'      => $request->rating,
            'body'        => $request->body,
            'media_path'  => $mediaPath,
            'media_type'  => $request->media_type,
            'video_start' => $request->video_start,
            'video_end'   => $request->video_end,
        ]);

        return back()->with('success', 'Ulasan kamu berhasil dikirim!');
    }

    public function update(Request $request, Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);

        $request->validate([
            'rating'      => 'required|integer|between:1,5',
            'body'        => 'nullable|string|max:1000',
            'media'       => 'nullable|file|mimes:jpg,jpeg,png,mp4,webm|max:51200',
            'media_type'  => 'nullable|in:image,video',
            'video_start' => 'nullable|integer|min:0',
            'video_end'   => 'nullable|integer|min:0',
        ]);

        $data = [
            'rating'      => $request->rating,
            'body'        => $request->body,
        ];

        // If a new media is uploaded
        if ($request->hasFile('media') || $request->filled('media_base64')) {
            if ($review->media_path) {
                Storage::disk('public')->delete($review->media_path);
            }
            
            if ($request->hasFile('media')) {
                $data['media_path'] = $request->file('media')->store('reviews', 'public');
            } else {
                try {
                    $image_parts = explode(";base64,", $request->media_base64);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1] ?? 'jpeg';
                    $image_base64 = base64_decode($image_parts[1]);
                    $path = 'reviews/' . uniqid() . '.' . $image_type;
                    Storage::disk('public')->put($path, $image_base64);
                    $data['media_path'] = $path;
                } catch (\Exception $e) {}
            }
            
            $data['media_type'] = $request->media_type;
            $data['video_start'] = $request->video_start;
            $data['video_end']   = $request->video_end;
        } elseif ($request->media_type == 'video' && $review->media_type == 'video') {
            // Update video start/end if media is still video and not replaced
            $data['video_start'] = $request->video_start;
            $data['video_end']   = $request->video_end;
        }

        $review->update($data);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        abort_unless($review->user_id === Auth::id(), 403);
        
        if ($review->media_path) {
            Storage::disk('public')->delete($review->media_path);
        }
        
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
