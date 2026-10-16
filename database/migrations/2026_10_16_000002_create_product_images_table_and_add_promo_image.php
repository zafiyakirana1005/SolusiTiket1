<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::dropIfExists('product_images');
    }
};
