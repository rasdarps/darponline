<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW stock_movements AS
            SELECT 
                purchase_details.id,
                products.id AS product_id,
                categories.id AS category_id,
                products.name AS product_name,
                categories.name AS category,
                units.name AS unit,
                'Purchase' AS transaction_type,
                purchases.date AS date,
                purchase_details.buying_qty AS quantity,
                users.name AS created_by
            FROM purchase_details
            JOIN products ON purchase_details.product_id = products.id
            JOIN categories ON purchase_details.category_id = categories.id
            JOIN units ON purchase_details.unit_id = units.id
            JOIN purchases ON purchase_details.purchase_id = purchases.id
            JOIN users ON purchases.created_by = users.id

            UNION ALL

            SELECT 
                invoice_details.id,
                products.id AS product_id,
                categories.id AS category_id,
                products.name AS product_name,
                categories.name AS category,
                units.name AS unit,
                'Sale' AS transaction_type,
                invoices.date AS date,
                invoice_details.selling_qty AS quantity,
                users.name AS created_by
            FROM invoice_details
            JOIN products ON invoice_details.product_id = products.id
            JOIN categories ON invoice_details.category_id = categories.id
            JOIN units ON products.unit_id = units.id
            JOIN invoices ON invoice_details.invoice_id = invoices.id
            JOIN users ON invoices.created_by = users.id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS stock_movements");
    }
};
