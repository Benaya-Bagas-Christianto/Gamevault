<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function fetchImage(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        try {
            $context = stream_context_create(['http' => ['ignore_errors' => true]]);
            $contents = file_get_contents($request->url, false, $context);
            if (!$contents) throw new \Exception('Failed to fetch');
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($contents);
            $base64 = base64_encode($contents);
            return response()->json(['success' => true, 'image' => "data:$mime;base64,$base64"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunduh gambar dari URL.'], 400);
        }
    }

    public function index(Request $request)
    {
        $query = Game::withCount('transactionDetails')->latest();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        $games = $query->paginate(5)->withQueryString();
        return response()->view('admin.games.index', compact('games'))
                         ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', '0');
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'genre'        => 'required|array|min:1',
            'genre.*'      => 'string|max:50',
            'developer'    => 'nullable|string|max:100',
            'publisher'    => 'nullable|string|max:100',
            'release_date' => 'nullable|date',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'    => 'boolean',
            'trailer_mp4'  => 'nullable|mimes:mp4|max:51200', // max 50MB
            'youtube_link' => 'nullable|url',
        ]);

        $data = $request->except('cover_image');
        $data['slug'] = Str::slug($request->title) . '-' . Str::random(6);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $game = Game::create($data);

        if ($request->hasFile('trailer_mp4')) {
            $game->galleries()->create([
                'type' => 'video',
                'path' => $request->file('trailer_mp4')->store('galleries', 'public')
            ]);
        }

        if ($request->filled('youtube_link')) {
            $game->galleries()->create([
                'type' => 'youtube',
                'path' => $request->youtube_link
            ]);
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan!');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'genre'        => 'required|array|min:1',
            'genre.*'      => 'string|max:50',
            'developer'    => 'nullable|string|max:100',
            'publisher'    => 'nullable|string|max:100',
            'release_date' => 'nullable|date',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'    => 'boolean',
            'trailer_mp4'  => 'nullable|mimes:mp4|max:51200',
            'youtube_link' => 'nullable|url',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($game->cover_image && Storage::disk('public')->exists($game->cover_image)) {
                Storage::disk('public')->delete($game->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $game->update($data);

        if ($request->hasFile('trailer_mp4')) {
            $game->galleries()->create([
                'type' => 'video',
                'path' => $request->file('trailer_mp4')->store('galleries', 'public')
            ]);
        }

        if ($request->filled('youtube_link')) {
            $game->galleries()->create([
                'type' => 'youtube',
                'path' => $request->youtube_link
            ]);
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil diperbarui!');
    }

    public function destroy(Game $game)
    {
        if ($game->cover_image && Storage::disk('public')->exists($game->cover_image)) {
            Storage::disk('public')->delete($game->cover_image);
        }

        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil dihapus.');
    }

    public function destroyGallery($id)
    {
        $gallery = GameGallery::findOrFail($id);
        
        if ($gallery->type == 'video' && Storage::disk('public')->exists($gallery->path)) {
            Storage::disk('public')->delete($gallery->path);
        }
        
        $gallery->delete();

        return back()->with('success', 'Preview berhasil dihapus.');
    }
}
