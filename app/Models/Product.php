<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Cho phép fill các trường từ form
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'quantity',
        'price',
        'features',
        'image'
    ];

    // Nếu dùng JSON cho features, cast về array
    protected $casts = [
        'features' => 'array',
    ];

    // Quan hệ: Product thuộc về Category (N-1)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // (Tuỳ chọn) Quan hệ 1-1: Product có ProductDetail
    // public function detail()
    // {
    //     return $this->hasOne(ProductDetail::class);
    // }
}
