<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE stock_ledgers MODIFY COLUMN movement_type ENUM(
            'purchase',
            'sale',
            'adjustment_increase',
            'adjustment_decrease',
            'purchase_cancel',
            'sale_cancel',
            'purchase_edit_increase',
            'purchase_edit_decrease'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_ledgers MODIFY COLUMN movement_type ENUM(
            'purchase',
            'sale',
            'adjustment_increase',
            'adjustment_decrease',
            'purchase_cancel',
            'sale_cancel'
        ) NOT NULL");
    }
};
