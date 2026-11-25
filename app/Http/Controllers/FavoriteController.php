<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Favorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        return Campaign::query()
            ->whereHas('favorites', fn ($query) => $query->where('user_id', $request->user()->id))
            ->with('frames')
            ->latest()
            ->paginate(12);
    }

    public function store(Request $request, Campaign $campaign): JsonResponse
    {
        Favorite::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'campaign_id' => $campaign->id,
        ]);

        return response()->json(['message' => 'Ditambahkan ke favorite.'], 201);
    }

    public function destroy(Request $request, Campaign $campaign): JsonResponse
    {
        Favorite::query()
            ->where('user_id', $request->user()->id)
            ->where('campaign_id', $campaign->id)
            ->delete();

        return response()->json(['message' => 'Dihapus dari favorite.']);
    }
}