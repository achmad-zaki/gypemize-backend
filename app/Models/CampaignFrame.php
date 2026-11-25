<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['campaign_id', 'name', 'format_type', 'frame_url'])]
class CampaignFrame extends Model
{
    protected $table = 'campaign_frame';

    public $timestamps = false;

    /** @return BelongsTo<Campaign, CampaignFrame> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}