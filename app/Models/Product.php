<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'code', 'description', 'price'];

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->inventories->sum('quantity');
    }
}