<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('description');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
            $table->string('cancellation_reason', 500)->nullable()->after('cancelled_by');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at', 'cancelled_by', 'cancellation_reason']);
        });
    }
};
