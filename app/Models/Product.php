<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_code',
        'vendor',
        'category',
        'description',
        'price',
        'duration_months',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_months' => 'integer',
    ];

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
