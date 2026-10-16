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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_number", 30)->unique();
            $table->foreignId("user_id")->constrained("users");
            $table->enum("service_mode", ["on_site", "delivery"]);
            $table->enum("status", 
                ['pending', 
                'confirmed', 
                'preparing', 
                'ready', 
                'delivered', 
                'completed', 
                'cancelled'])->default("pending");
            $table->text("delivery_address")->nullable();
            $table->timestamp("arrival_at")->nullable();
            $table->text("customer_note")->nullable();
            $table->unsignedInteger("subtotal_fcfa")->default(0);
            $table->unsignedInteger("discount_fcfa")->default(0);
            $table->unsignedInteger("delivery_fee_fcfa")->default(0);
            $table->unsignedInteger("total_fcfa")->default(0);
            $table->unsignedInteger("loyalty_points_used")->default(0);
            $table->unsignedInteger("loyalty_points_earned")->default(0);
            $table->timestamps();
            $table->timestamp("complted_at")->nullable();
            $table->index(["user_id", "created_at"]);
            $table->index(["status", 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
