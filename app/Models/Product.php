<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'cateogry_id',
        'name',
        'price',
        'stock',
        'description',
        'image',
        'estimated_time',
    ];
}
