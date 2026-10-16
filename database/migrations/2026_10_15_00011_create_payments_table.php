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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders");
            $table->string("provider", 40);
            $table->string("provider_transaction_id", 150)->nullable();
            $table->enum("method", ['mobile_money', 'card', 'cash', 'test']);
            $table->enum("status", ['pending', 'paid', 'failed', 'refunded'])->default("pending");
            $table->unsignedInteger("amount_fcfa");
            $table->timestamp("paid_at")->nullable();
            $table->timestamp("created_at")->useCurrent();
            $table->unique(["provider", "provider_transaction_id"]);
            $table->index("status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
