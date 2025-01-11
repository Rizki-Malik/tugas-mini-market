<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'inventory_id',
        'previous_quantity',
        'new_quantity',
        'movement_type',
        'notes',
        'user_id',
        'related_inventory_id',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedInventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'related_inventory_id');
    }
}