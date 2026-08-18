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
        if (!Schema::hasTable('purchase_edit_audits')) {
            Schema::create('purchase_edit_audits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
                $table->string('purchase_no');
                $table->unsignedBigInteger('edited_by')->nullable();
                $table->timestamp('edited_at')->useCurrent();
                $table->text('reason')->nullable();
                $table->decimal('before_total', 12, 2)->default(0);
                $table->decimal('after_total', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('edited_by')->references('id')->on('users')->nullOnDelete();
                $table->index(['purchase_id', 'edited_at']);
            });
        }

        if (!Schema::hasTable('purchase_edit_audit_lines')) {
            Schema::create('purchase_edit_audit_lines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_edit_audit_id')->constrained('purchase_edit_audits')->cascadeOnDelete();
                $table->unsignedBigInteger('purchase_detail_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('product_name_snapshot')->nullable();
                $table->double('quantity_from')->default(0);
                $table->double('quantity_to')->default(0);
                $table->decimal('unit_cost_from', 12, 2)->default(0);
                $table->decimal('unit_cost_to', 12, 2)->default(0);
                $table->decimal('line_amount_from', 12, 2)->default(0);
                $table->decimal('line_amount_to', 12, 2)->default(0);
                $table->double('quantity_delta')->default(0);
                $table->decimal('amount_delta', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
                $table->index(['purchase_edit_audit_id', 'purchase_detail_id'], 'purchase_audit_line_lookup_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_edit_audit_lines');
        Schema::dropIfExists('purchase_edit_audits');
    }
};
