<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'brand',
        'category_id',
        'description',
        'price',
        'stock',
        'image',
        'screen',
        'cpu',
        'ram',
        'storage',
        'battery',
        'os',
    ];

    public $timestamps = true;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
