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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->nullable()->constrained("menu_categories")->nullOnDelete();
            $table->string("name");
            $table->text("description");
            $table->unsignedInteger("price_fcfa");
            $table->string("image_url", 500)->nullable();
            $table->boolean("is_available")->default(true);
            $table->boolean("is_daily_special")->default(false);
            $table->timestamps();
            $table->index(["is_available", "category_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
