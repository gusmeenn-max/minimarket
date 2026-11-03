<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ["category_id", "code", "name", "unit", "stock", "minimum_stock", "description"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    /** Status stok: habis, menipis (<= stok minimum), atau aman. */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Habis';
        }

        return $this->stock <= $this->minimum_stock ? 'Menipis' : 'Aman';
    }

    public function getStockStatusClassAttribute(): string
    {
        return match ($this->stock_status) {
            'Habis' => 'badge-soft-danger',
            'Menipis' => 'badge-soft-warning',
            default => 'badge-soft-success',
        };
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }
}
