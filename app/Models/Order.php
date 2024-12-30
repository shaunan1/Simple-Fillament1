<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'orders';

    // Tentukan atribut yang dapat diisi massal
    protected $fillable = [
        'customer_id',
        'payment_id',
        'total',
        'note',
    ];

    // Definisikan relasi dengan model Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Definisikan relasi dengan model Payment
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Definisikan relasi dengan model OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}