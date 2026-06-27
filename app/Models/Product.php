<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Yeh line Laravel ko batati hai ke in columns mein data insert karne ki ijazat hai
    protected $fillable = ['name', 'category', 'price', 'stock_quantity'];
}