<?php

namespace App\Models;

use App\Models\Product; // Ensure the correct namespace is used
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'order_details';

    // Tentukan atribut yang dapat diisi massal
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Definisikan relasi dengan model Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Definisikan relasi dengan model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}