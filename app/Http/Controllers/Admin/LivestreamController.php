<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use App\Models\Product;
use Illuminate\Http\Request;

class LivestreamController extends Controller
{
    public function index()
    {
        $livestreams = Livestream::latest()->paginate(10);

        return view('admin.livestreams.index', compact('livestreams'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.livestreams.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|max:500',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|array',
        ]);

        $videoId = $this->extractYoutubeVideoId($request->youtube_url);

        if (!$videoId) {
            return back()
                ->withInput()
                ->with('error', 'Link YouTube không hợp lệ.');
        }

        // Nếu bật livestream mới, tắt các livestream khác
        if ($request->has('is_active')) {
            Livestream::query()->update(['is_active' => false]);
        }

        $livestream = Livestream::create([
            'title' => $request->title,
            'description' => $request->description,
            'youtube_url' => $request->youtube_url,
            'youtube_video_id' => $videoId,
            'is_active' => $request->has('is_active'),
            'started_at' => $request->has('is_active') ? now() : null,
            'created_by' => auth()->id(),
        ]);

        $livestream->products()->sync($request->product_ids ?? []);

        return redirect()
            ->route('admin.livestreams.index')
            ->with('success', 'Tạo livestream thành công.');
    }

    public function edit(Livestream $livestream)
    {
        $products = Product::orderBy('name')->get();

        return view('admin.livestreams.edit', compact('livestream', 'products'));
    }

    public function update(Request $request, Livestream $livestream)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|max:500',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|array',
        ]);

        $videoId = $this->extractYoutubeVideoId($request->youtube_url);

        if (!$videoId) {
            return back()
                ->withInput()
                ->with('error', 'Link YouTube không hợp lệ.');
        }

        if ($request->has('is_active')) {
            Livestream::where('id', '!=', $livestream->id)
                ->update(['is_active' => false]);
        }

        $livestream->update([
            'title' => $request->title,
            'description' => $request->description,
            'youtube_url' => $request->youtube_url,
            'youtube_video_id' => $videoId,
            'is_active' => $request->has('is_active'),
            'started_at' => $request->has('is_active') && !$livestream->started_at ? now() : $livestream->started_at,
            'ended_at' => !$request->has('is_active') ? now() : null,
        ]);

        $livestream->products()->sync($request->product_ids ?? []);

        return redirect()
            ->route('admin.livestreams.index')
            ->with('success', 'Cập nhật livestream thành công.');
    }

    public function destroy(Livestream $livestream)
    {
        $livestream->delete();

        return back()->with('success', 'Xóa livestream thành công.');
    }

    private function extractYoutubeVideoId(string $url): ?string
    {
        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}