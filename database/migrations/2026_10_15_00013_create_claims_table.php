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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("orders");
            $table->foreignId("user_id")->constrained("users");
            $table->string("subject", 150);
            $table->text("description")->nullable();
            $table->enum("status", ['open', 'in_progress', 'resolved', 'rejected'])->default("open");
            $table->text("response")->nullable();
            $table->foreignId("handled_by")->nullable()->constrained("users")->nullOnDelete();
            $table->timestamps();
            $table->index(["status", "created_at"]);
            $table->index(["user_id", "created_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
