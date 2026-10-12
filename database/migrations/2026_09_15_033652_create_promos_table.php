<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();

            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->unsignedBigInteger('value'); // persen (0-100) atau nominal rupiah
            $table->unsignedBigInteger('max_discount')->nullable(); // cap untuk tipe percentage
            $table->unsignedBigInteger('min_purchase')->default(0);

            $table->unsignedInteger('quota')->nullable(); // null = tak terbatas
            $table->unsignedInteger('used_count')->default(0);

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
