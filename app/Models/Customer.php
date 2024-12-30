<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi
    protected $table = 'customers';

    // Tentukan atribut yang dapat diisi massal
    protected $fillable = [
        'name',
        'gender',
        'email',
        'phone',
        'birthday',
    ];

    // Jika Anda ingin menambahkan atribut yang tidak dapat diisi massal
    // protected $guarded = ['id', 'created_at', 'updated_at'];
}