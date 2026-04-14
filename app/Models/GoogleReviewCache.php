<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleReviewCache extends Model
{
    protected $fillable = [
        'place_id',
        'reviews_data',
        'fetched_at',
    ];

    protected $casts = [
        'reviews_data' => 'array',
        'fetched_at' => 'datetime',
    ];
}
