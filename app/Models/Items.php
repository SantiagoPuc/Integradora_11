<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'created',
        'modified',
    ];

    public $timestamps = false; // Desactiva timestamps automáticos si no usas created_at y updated_at
}
