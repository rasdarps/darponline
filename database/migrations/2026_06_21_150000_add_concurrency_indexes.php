<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Products: index for stock queries during concurrent sales
        Schema::table('products', function (Blueprint $table) {
            $table->index('quantity');
            $table->index(['category_id', 'quantity']);
            $table->index('created_by');
        });

        // Payments: index for status lookups
        Schema::table('payments', function (Blueprint $table) {
            $table->index('paid_status');
            $table->index(['invoice_id', 'paid_status']);
        });

        // Invoice Details: composite index for line-item lookups
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->index(['invoice_id', 'product_id']);
            $table->index('product_id');
        });

        // Invoices: index for user-based queries and date filtering
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['created_by', 'date']);
            $table->index('created_by');
        });

        // Purchase Details: same pattern as invoice details for consistency
        if (Schema::hasTable('purchase_details')) {
            Schema::table('purchase_details', function (Blueprint $table) {
                $table->index(['purchase_id', 'product_id']);
                $table->index('product_id');
            });
        }

        // Purchases: user and date indexing for consistency
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->index(['created_by', 'date']);
                $table->index('created_by');
            });
        }

        // Purchase Payments: status indexing for consistency
        if (Schema::hasTable('purchase_payments')) {
            Schema::table('purchase_payments', function (Blueprint $table) {
                $table->index('paid_status');
                $table->index(['purchase_id', 'paid_status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
            $table->dropIndex(['category_id', 'quantity']);
            $table->dropIndex(['created_by']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['paid_status']);
            $table->dropIndex(['invoice_id', 'paid_status']);
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropIndex(['invoice_id', 'product_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['created_by', 'date']);
            $table->dropIndex(['created_by']);
        });

        if (Schema::hasTable('purchase_details')) {
            Schema::table('purchase_details', function (Blueprint $table) {
                $table->dropIndex(['purchase_id', 'product_id']);
                $table->dropIndex(['product_id']);
            });
        }

        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropIndex(['created_by', 'date']);
                $table->dropIndex(['created_by']);
            });
        }

        if (Schema::hasTable('purchase_payments')) {
            Schema::table('purchase_payments', function (Blueprint $table) {
                $table->dropIndex(['paid_status']);
                $table->dropIndex(['purchase_id', 'paid_status']);
            });
        }
    }
};
