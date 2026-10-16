<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders")->cascadeOnDelete();
            $table->foreignId("menu_item_id")->nullable()->constrained("menu_items")->nullOnDelete();
            $table->string("item_name", 150);
            $table->unsignedInteger("unit_price_fcfa");
            $table->unsignedInteger("quantity");
            $table->unsignedInteger("line_total_fcfa");
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
