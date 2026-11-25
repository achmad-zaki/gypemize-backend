<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignFrame;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function index(): JsonResponse
    {
        $campaigns = Campaign::query()
            ->with('frames')
            ->where('permission', 'published')
            ->latest()
            ->paginate(12);

        return $this->success(
            data: $campaigns->items(),
            message: 'Daftar campaign berhasil diambil.',
            meta: [
                'current_page' => $campaigns->currentPage(),
                'from' => $campaigns->firstItem(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'to' => $campaigns->lastItem(),
                'total' => $campaigns->total(),
            ],
            links: [
                'first' => $campaigns->url(1),
                'last' => $campaigns->url($campaigns->lastPage()),
                'prev' => $campaigns->previousPageUrl(),
                'next' => $campaigns->nextPageUrl(),
            ],
        );
    }

    public function show(Campaign $campaign): JsonResponse
    {
        return $this->success(
            data: $campaign->load('frames'),
            message: 'Detail campaign berhasil diambil.',
            links: [
                'self' => url("/api/campaigns/{$campaign->id}"),
                'collection' => url('/api/campaigns'),
            ],
        );
    }

   public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:campaign,slug'],
            'link' => ['required', 'url', 'unique:campaign,link'],
            'description' => ['nullable', 'string'],
            'permission' => ['required', 'in:draft,published,private'],
            'frame' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $campaign = Campaign::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'link' => $validated['link'],
            'description' => $validated['description'] ?? null,
            'permission' => $validated['permission'],
        ]);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = $disk->putFile('frames', $request->file('frame'));

        CampaignFrame::create([
            'campaign_id' => $campaign->id,
            'name' => 'Frame utama',
            'format_type' => 1,
            'frame_url' => $disk->url($path),
        ]);

          return response()->json([
            'success' => true,
            'message' => 'Campaign berhasil dibuat.',
            'data' => $campaign->load('frames'),
        ], 201);
    }
    public function update(Request $request, Campaign $campaign): JsonResponse
    {
        abort_unless($campaign->user_id === $request->user()->id, 403, 'Kamu bukan pemilik campaign ini.');

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('campaign', 'slug')->ignore($campaign->id),
            ],
            'link' => [
                'sometimes',
                'required',
                'url',
                Rule::unique('campaign', 'link')->ignore($campaign->id),
            ],
            'description' => ['nullable', 'string'],
            'permission' => ['sometimes', 'required', 'in:draft,published,private'],
        ]);

        $campaign->update($validated);

        return $this->success(
            data: $campaign->load('frames'),
            message: 'Campaign berhasil diperbarui.',
            links: [
                'self' => url("/api/campaigns/{$campaign->id}"),
                'collection' => url('/api/campaigns'),
            ],
        );
    }

    public function destroy(Request $request, Campaign $campaign): JsonResponse
    {
        abort_unless($campaign->user_id === $request->user()->id, 403, 'Kamu bukan pemilik campaign ini.');

        $campaign->delete();

        return $this->success(
            data: null,
            message: 'Campaign dihapus.',
            links: [
                'collection' => url('/api/campaigns'),
            ],
        );
    }
}