-- POS-only direct schema cleanup for existing databases (MySQL/MariaDB)
-- Safe to run multiple times.

SET @schema_name = DATABASE();

-- Ensure the foreign keys have simple supporting indexes before removing legacy composites.
ALTER TABLE `payments` ADD INDEX `payments_sale_id_index` (`sale_id`);
ALTER TABLE `purchase_payments` ADD INDEX `purchase_payments_purchase_id_index` (`purchase_id`);

-- Drop legacy indexes if they exist.
SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `payments` DROP INDEX `payments_paid_status_index`',
        'SELECT "skip payments_paid_status_index"'
    )
    FROM information_schema.statistics
    WHERE table_schema = @schema_name
      AND table_name = 'payments'
      AND index_name = 'payments_paid_status_index'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `payments` DROP INDEX `payments_sale_id_paid_status_index`',
        'SELECT "skip payments_sale_id_paid_status_index"'
    )
    FROM information_schema.statistics
    WHERE table_schema = @schema_name
      AND table_name = 'payments'
      AND index_name = 'payments_sale_id_paid_status_index'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `purchase_payments` DROP INDEX `purchase_payments_paid_status_index`',
        'SELECT "skip purchase_payments_paid_status_index"'
    )
    FROM information_schema.statistics
    WHERE table_schema = @schema_name
      AND table_name = 'purchase_payments'
      AND index_name = 'purchase_payments_paid_status_index'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `purchase_payments` DROP INDEX `purchase_payments_purchase_id_paid_status_index`',
        'SELECT "skip purchase_payments_purchase_id_paid_status_index"'
    )
    FROM information_schema.statistics
    WHERE table_schema = @schema_name
      AND table_name = 'purchase_payments'
      AND index_name = 'purchase_payments_purchase_id_paid_status_index'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Drop legacy columns if they exist.
SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `payments` DROP COLUMN `paid_status`',
        'SELECT "skip payments.paid_status"'
    )
    FROM information_schema.columns
    WHERE table_schema = @schema_name
      AND table_name = 'payments'
      AND column_name = 'paid_status'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `payments` DROP COLUMN `due_amount`',
        'SELECT "skip payments.due_amount"'
    )
    FROM information_schema.columns
    WHERE table_schema = @schema_name
      AND table_name = 'payments'
      AND column_name = 'due_amount'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `purchase_payments` DROP COLUMN `paid_status`',
        'SELECT "skip purchase_payments.paid_status"'
    )
    FROM information_schema.columns
    WHERE table_schema = @schema_name
      AND table_name = 'purchase_payments'
      AND column_name = 'paid_status'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (
    SELECT IF(
        COUNT(*) > 0,
        'ALTER TABLE `purchase_payments` DROP COLUMN `due_amount`',
        'SELECT "skip purchase_payments.due_amount"'
    )
    FROM information_schema.columns
    WHERE table_schema = @schema_name
      AND table_name = 'purchase_payments'
      AND column_name = 'due_amount'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Remove legacy detail tables.
DROP TABLE IF EXISTS `payment_details`;
DROP TABLE IF EXISTS `purchase_payment_details`;

-- Verification snapshot.
SELECT table_name, column_name
FROM information_schema.columns
WHERE table_schema = @schema_name
  AND table_name IN ('payments', 'purchase_payments')
  AND column_name IN ('paid_status', 'due_amount');

SELECT table_name
FROM information_schema.tables
WHERE table_schema = @schema_name
  AND table_name IN ('payment_details', 'purchase_payment_details');
