<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = ['name', 'brand', 'size', 'stock_quantity', 'price'];

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function scopeLowStock(Builder $query): void
    {
        $query->where('stock_quantity', '<=', self::LOW_STOCK_THRESHOLD);
    }
}
