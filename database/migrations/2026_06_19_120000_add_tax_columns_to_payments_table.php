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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('taxable_amount', 12, 2)->default(0)->after('discount_amount');
            $table->decimal('nhil_amount', 12, 2)->default(0)->after('taxable_amount');
            $table->decimal('getfund_amount', 12, 2)->default(0)->after('nhil_amount');
            $table->decimal('vat_amount', 12, 2)->default(0)->after('getfund_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['taxable_amount', 'nhil_amount', 'getfund_amount', 'vat_amount']);
        });
    }
};
