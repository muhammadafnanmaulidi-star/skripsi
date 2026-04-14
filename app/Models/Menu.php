<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id', // penting ditambahkan
        'name',
        'main_ingredient',
        'description',
        'price',
        'discount_percent',
        'image',
        'image_position',
        'image_zoom',
        'is_available',
        'special_type',
        'stock',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percent' => 'integer',
        'image_zoom' => 'float',
        'is_available' => 'boolean',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promos()
    {
        return $this->hasMany(Promo::class);
    }
}
