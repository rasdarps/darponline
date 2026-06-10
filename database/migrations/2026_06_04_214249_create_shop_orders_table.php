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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('billing_address_id')->constrained('customer_addresses')->onDelete('restrict');
            $table->foreignId('shipping_address_id')->constrained('customer_addresses')->onDelete('restrict');
            $table->enum('status', [
                'pending', 'processing', 'confirmed', 'shipped', 
                'delivered', 'cancelled', 'refunded', 'failed'
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered'])->default('pending');
            $table->string('payment_method');
            $table->string('payment_transaction_id')->nullable();
            $table->string('shipping_method');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};
