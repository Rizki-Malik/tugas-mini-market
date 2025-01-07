<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'code', 'description', 'price'];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}