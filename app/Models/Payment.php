<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'payments';

    // Tentukan atribut yang dapat diisi massal
    protected $fillable = [
        'name',
    ];

    // Definisikan relasi dengan model Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}