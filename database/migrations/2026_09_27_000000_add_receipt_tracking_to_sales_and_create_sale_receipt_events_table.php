<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('delivery_code', 40)->nullable()->unique()->after('description');
            $table->timestamp('printed_at')->nullable()->after('delivery_code');
            $table->unsignedBigInteger('printed_by')->nullable()->after('printed_at');
            $table->timestamp('delivered_at')->nullable()->after('printed_by');
            $table->unsignedBigInteger('delivered_by')->nullable()->after('delivered_at');

            $table->index('printed_at');
            $table->index('delivered_at');
            $table->index('printed_by');
            $table->index('delivered_by');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('printed_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('delivered_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('sale_receipt_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_type', 30);
            $table->string('reason', 500)->nullable();
            $table->timestamp('event_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'event_type']);
            $table->index(['operator_id', 'event_type']);
            $table->index('event_at');
        });

        DB::table('sales')
            ->whereNull('delivery_code')
            ->orderBy('id')
            ->chunkById(100, function ($sales) {
                foreach ($sales as $sale) {
                    $deliveryCode = sprintf(
                        'DLV-%08d-%s',
                        (int) $sale->id,
                        strtoupper(substr(bin2hex(random_bytes(3)), 0, 6))
                    );

                    DB::table('sales')
                        ->where('id', $sale->id)
                        ->update(['delivery_code' => $deliveryCode]);
                }
            }, 'id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_receipt_events');

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['printed_by']);
            $table->dropForeign(['delivered_by']);
            $table->dropIndex(['printed_at']);
            $table->dropIndex(['delivered_at']);
            $table->dropIndex(['printed_by']);
            $table->dropIndex(['delivered_by']);
            $table->dropUnique(['delivery_code']);
            $table->dropColumn(['delivery_code', 'printed_at', 'printed_by', 'delivered_at', 'delivered_by']);
        });
    }
};