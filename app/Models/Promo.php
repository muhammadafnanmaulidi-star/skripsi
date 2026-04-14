<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'title',
        'description',
        'image',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];
}