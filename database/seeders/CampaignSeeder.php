<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignFrame;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrFail();

        $campaign = Campaign::query()->updateOrCreate(
            ['slug' => 'twibbon-contoh'],
            [
                'user_id' => $user->id,
                'title' => 'Twibbon Contoh',
                'link' => 'https://example.test/campaign/twibbon-contoh',
                'description' => 'Campaign contoh untuk dicoba lewat Postman.',
                'permission' => 'published',
            ],
        );

        CampaignFrame::query()->updateOrCreate(
            ['campaign_id' => $campaign->id],
            [
                'name' => 'Frame utama',
                'format_type' => 1,
                'frame_url' => 'https://placehold.co/600x600/ec4899/ffffff?text=Twibbon+Contoh',
            ],
        );
    }
}