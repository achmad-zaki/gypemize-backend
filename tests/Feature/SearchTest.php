<?php

it('mengembalikan envelope pencarian dengan data kosong saat q tidak diisi', function () {
    $this->getJson('/api/search')
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['campaigns', 'users'],
            'meta' => ['query', 'campaigns_count', 'users_count'],
            'links' => ['self'],
        ])
        ->assertJson([
            'success' => true,
            'data' => ['campaigns' => [], 'users' => []],
            'meta' => ['query' => '', 'campaigns_count' => 0, 'users_count' => 0],
        ]);
});