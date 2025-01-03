<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'gender',
        'email',
        'phone',
        'birthday',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}