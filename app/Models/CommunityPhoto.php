<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'caption',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active photos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordering (Newest first)
    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc');
    }
}
