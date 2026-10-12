<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-2024-8921
            $table->string('trx_id')->nullable(); // LMD-TRX-89210-BDG
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('total')->default(0);

            $table->string('payment_method')->nullable(); // BCA VA, QRIS, GoPay, dst.
            $table->enum('payment_status', ['pending', 'paid', 'expired', 'refunded'])->default('pending');

            $table->enum('fulfillment_status', [
                'pending_payment', 'paid', 'packing', 'ready_to_ship', 'shipped', 'completed', 'cancelled',
            ])->default('pending_payment');

            $table->string('courier')->nullable(); // JNE, J&T, dst.
            $table->string('courier_service')->nullable(); // YES, REG, dst
            $table->string('tracking_number')->nullable();

            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_province')->nullable();

            $table->text('buyer_note')->nullable();
            $table->boolean('flag_gift_card')->default(false);
            $table->boolean('flag_extra_wrap')->default(false);

            $table->string('source')->default('Website Store (Desktop)');
            $table->string('customer_ip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
