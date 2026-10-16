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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained("roles")->cascadeOnDelete();
            $table->string("first_name", 80);
            $table->string("last_name", 80);
            $table->string("email", 255)->unique();
            $table->string("phone", 30)->nullable();
            $table->string("password", 255);
            $table->string("delivery_address")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->index(['role_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
