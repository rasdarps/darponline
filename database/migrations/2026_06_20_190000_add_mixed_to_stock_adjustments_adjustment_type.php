<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE stock_adjustments MODIFY adjustment_type ENUM('increase', 'decrease', 'mixed') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE stock_adjustments MODIFY adjustment_type ENUM('increase', 'decrease') NOT NULL");
    }
};
