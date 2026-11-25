<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'slug', 'link', 'description', 'permission'])]
class Campaign extends Model
{
    protected $table = 'campaign';

    /** @return HasMany<CampaignFrame> */
    public function frames(): HasMany
    {
        return $this->hasMany(CampaignFrame::class);
    }

    /** @return HasMany<Favorite> */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}