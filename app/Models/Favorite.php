<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'campaign_id'])]
class Favorite extends Model
{
    protected $table = 'favorite';

    public $timestamps = false;

    /** @return BelongsTo<User, Favorite> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Campaign, Favorite> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}