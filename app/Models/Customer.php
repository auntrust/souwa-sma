<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'furigana', 'tel', 'email', 'todofuken', 'co_name', 'co_tel', 'co_busho', 'co_post', 'is_delivery', 'optin_at', 'optin_method'];
}
