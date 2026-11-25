<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $query = trim($validated['q'] ?? '');

        if ($query === '') {
            return $this->success(
                data: ['campaigns' => [], 'users' => []],
                message: 'Hasil pencarian.',
                meta: ['query' => '', 'campaigns_count' => 0, 'users_count' => 0],
                links: ['self' => url('/api/search')],
            );
        }

        $term = '%'.addcslashes($query, '%_\\').'%';

        $campaigns = Campaign::query()
            ->with('frames')
            ->where('permission', 'published')
            ->where('title', 'like', $term)
            ->latest()
            ->limit(10)
            ->get();

        $users = User::query()
            ->where('name', 'like', $term)
            ->select('id', 'name')
            ->limit(10)
            ->get();

        return $this->success(
            data: [
                'campaigns' => $campaigns,
                'users' => $users,
            ],
            message: 'Hasil pencarian.',
            meta: [
                'query' => $query,
                'campaigns_count' => $campaigns->count(),
                'users_count' => $users->count(),
            ],
            links: [
                'self' => url('/api/search').'?q='.urlencode($query),
            ],
        );
    }
}