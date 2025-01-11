<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->enum('movement_type', [
                'add',          
                'subtract',     
                'set',          
                'transfer_in',  
                'transfer_out', 
                'adjustment',   
                'sale',        
                'return'       
            ]);
            $table->foreignId('related_inventory_id')
                ->nullable()
                ->constrained('inventories')
                ->onDelete('restrict');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('movement_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};