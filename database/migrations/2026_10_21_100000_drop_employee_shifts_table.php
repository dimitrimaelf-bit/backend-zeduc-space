<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('employee_shifts');
    }

    public function down(): void
    {
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('shift_date');
            $table->string('service', 80)->nullable();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamps();
            $table->index(['shift_date', 'status']);
            $table->index(['user_id', 'shift_date']);
        });
    }
};
