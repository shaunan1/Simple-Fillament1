<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'products';

    // Tentukan atribut yang dapat diisi massal
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'image',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // Definisikan relasi dengan model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Definisikan relasi dengan model OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}