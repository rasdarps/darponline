<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $guardName = config('permission.defaults.guard_name', config('auth.defaults.guard', 'web'));

        $permissions = [
            'role-list','role-create','role-edit','role-delete',
            'unit-list','unit-create','unit-edit','unit-delete',
            'category-list','category-create','category-edit','category-delete',
            'user-list','user-create', 'user-edit','user-delete',
         ];
      
         foreach ($permissions as $permission) {
                Permission::findOrCreate($permission, $guardName);
         }


          //Products Management Permissions
        $products_management_permissions = [
            'product-list','product-create','product-edit','product-delete',
            
        ];

         // Create products management permissions in the database if they don't already exist
        foreach ($products_management_permissions as $product_permission) {
            Permission::findOrCreate($product_permission, $guardName);
        }


        $supplier_management_permissions = [
            'supplier-list','supplier-create','supplier-edit','supplier-delete',
           
        ];


        // Create supplier management permissions in the database if they don't already exist
        foreach ($supplier_management_permissions as $supplier_permission) {
            Permission::findOrCreate($supplier_permission, $guardName);
        }

        $customers_management_permissions = [ 
            'customer-list','customer-create','customer-edit','customer-delete',
           
        ];


        // Create customer management permissions in the database if they don't already exist
        foreach ($customers_management_permissions as $customer_permission) {
            Permission::findOrCreate($customer_permission, $guardName);
        }

          $transactions_management_permissions = [ 
              'sale-list','sale-create','sale-cancel','sale-print',
                'purchase-list','purchase-create','purchase-cancel','purchase-print',
        ];

        $expense_management_permissions = [
            'expense-list','expense-create','expense-edit','expense-delete',
        ];

        $expense_category_permissions = [
            'expense-category-list','expense-category-create','expense-category-edit','expense-category-delete',
        ];

        $stock_adjustment_permissions = [
            'stock-adjustment-list','stock-adjustment-create','stock-adjustment-edit','stock-adjustment-delete','stock_adjustment-print',
        ];

        $company_management_permissions = [
            'company-list','company-create','company-edit','company-delete',
        ];

        $product_price_permissions = [
            'product-price-list','product-price-edit','product-price-delete','product-price-history-list',
        ];

        $cash_reconciliation_permissions = [
            'cash-reconciliation-list','cash-reconciliation-create','cash-reconciliation-edit','cash-reconciliation-show',
            'cash-reconciliation-approve','cash-reconciliation-reverse-approval',
        ];

        $report_permissions = [
            'report-profit','report-expense','report-stock-adjustment','report-summary-ledger',
        ];

        $stock_report_permissions = [
            'stock-report','stock-ledger-report','stock-product-trail-report','stock-value-report',
        ];

        $dashboard_permissions = [
            'dashboard-view',
        ];

        $settings_permissions = [
            'settings-manage',
            'daily-transaction-email-send',
            'user-audit-view',
            'user-filter-view',
        ];


        // Create transactions management permissions in the database if they don't already exist
        foreach ($transactions_management_permissions as $transaction_permission) {
            Permission::findOrCreate($transaction_permission, $guardName);
        }

        // Create expense management permissions in the database if they don't already exist
        foreach ($expense_management_permissions as $expense_permission) {
            Permission::findOrCreate($expense_permission, $guardName);
        }

        // Create expense category permissions in the database if they don't already exist
        foreach ($expense_category_permissions as $expense_category_permission) {
            Permission::findOrCreate($expense_category_permission, $guardName);
        }

        // Create stock adjustment permissions in the database if they don't already exist
        foreach ($stock_adjustment_permissions as $stock_adjustment_permission) {
            Permission::findOrCreate($stock_adjustment_permission, $guardName);
        }

        // Create company management permissions
        foreach ($company_management_permissions as $company_permission) {
            Permission::findOrCreate($company_permission, $guardName);
        }

        // Create product price permissions
        foreach ($product_price_permissions as $product_price_permission) {
            Permission::findOrCreate($product_price_permission, $guardName);
        }

        // Create cash reconciliation permissions
        foreach ($cash_reconciliation_permissions as $cash_reconciliation_permission) {
            Permission::findOrCreate($cash_reconciliation_permission, $guardName);
        }

        // Create report permissions
        foreach ($report_permissions as $report_permission) {
            Permission::findOrCreate($report_permission, $guardName);
        }

        // Create stock report permissions
        foreach ($stock_report_permissions as $stock_report_permission) {
            Permission::findOrCreate($stock_report_permission, $guardName);
        }

        // Create dashboard permissions
        foreach ($dashboard_permissions as $dashboard_permission) {
            Permission::findOrCreate($dashboard_permission, $guardName);
        }

        // Create settings permissions
        foreach ($settings_permissions as $settings_permission) {
            Permission::findOrCreate($settings_permission, $guardName);
        }

        // Find or retrieve the Super Admin role (must exist)
        $role = Role::findOrCreate('Super Admin', $guardName);
        
        // Create Manager role if it doesn't exist, or retrieve it if it does
        $manager_role = Role::findOrCreate('Manager', $guardName);

        // Create Cashier and Supervisor roles if they don't exist
        $cashier_role = Role::findOrCreate('Cashier', $guardName);
        $supervisor_role = Role::findOrCreate('Supervisor', $guardName);


         // Assign products permissions to Super Admin and Manager roles
        foreach ($products_management_permissions as $product_permission) {
            // Check if Super Admin doesn't have the permission, then assign it
            if (!$role->hasPermissionTo($product_permission)) {
                $role->givePermissionTo($product_permission);
            }
            // Check if Manager doesn't have the permission, then assign it
            if (!$manager_role->hasPermissionTo($product_permission)) {
                $manager_role->givePermissionTo($product_permission);
            }
        }


          // Assign suppliers permissions to Super Admin and Manager roles
        foreach ($supplier_management_permissions as $supplier_permission) {
            // Check if Super Admin doesn't have the permission, then assign it
            if (!$role->hasPermissionTo($supplier_permission)) {
                $role->givePermissionTo($supplier_permission);
            }

            // Check if Manager doesn't have the permission, then assign it
            if (!$manager_role->hasPermissionTo($supplier_permission)) {
                $manager_role->givePermissionTo($supplier_permission);
            }
           
        }


           // Assign customers permissions to Super Admin and Manager roles
        foreach ($customers_management_permissions as $customer_permission) {
            // Check if Super Admin doesn't have the permission, then assign it
            if (!$role->hasPermissionTo($customer_permission)) {
                $role->givePermissionTo($customer_permission);
            }
            // Check if Manager doesn't have the permission, then assign it
            if (!$manager_role->hasPermissionTo($customer_permission)) {
                $manager_role->givePermissionTo($customer_permission);
            }

        }

        
           // Assign transactions permissions to Super Admin and Manager roles
        foreach ($transactions_management_permissions as $transaction_permission) {
            // Check if Super Admin doesn't have the permission, then assign it
            if (!$role->hasPermissionTo($transaction_permission)) {
                $role->givePermissionTo($transaction_permission);
            }
            // Check if Manager doesn't have the permission, then assign it
            if (!$manager_role->hasPermissionTo($transaction_permission)) {
                $manager_role->givePermissionTo($transaction_permission);
            }
        }

        // Assign expense permissions to Super Admin and Manager roles
        foreach ($expense_management_permissions as $expense_permission) {
            if (!$role->hasPermissionTo($expense_permission)) {
                $role->givePermissionTo($expense_permission);
            }

            if (!$manager_role->hasPermissionTo($expense_permission)) {
                $manager_role->givePermissionTo($expense_permission);
            }
        }

        // Assign expense category permissions to Super Admin and Manager roles
        foreach ($expense_category_permissions as $expense_category_permission) {
            if (!$role->hasPermissionTo($expense_category_permission)) {
                $role->givePermissionTo($expense_category_permission);
            }

            if (!$manager_role->hasPermissionTo($expense_category_permission)) {
                $manager_role->givePermissionTo($expense_category_permission);
            }
        }

        // Assign stock adjustment permissions to Super Admin and Manager roles
        foreach ($stock_adjustment_permissions as $stock_adjustment_permission) {
            if (!$role->hasPermissionTo($stock_adjustment_permission)) {
                $role->givePermissionTo($stock_adjustment_permission);
            }

            if (!$manager_role->hasPermissionTo($stock_adjustment_permission)) {
                $manager_role->givePermissionTo($stock_adjustment_permission);
            }
        }


         // Assign basic permissions to Super Admin role only
         foreach ($permissions as $permission) {

            // Check if Super Admin doesn't have the permission, then assign it
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
          
        }

        // Assign additional permissions to Super Admin role only
        $additional_permissions = array_merge(
            $company_management_permissions,
            $product_price_permissions,
            $cash_reconciliation_permissions,
            $report_permissions,
            $stock_report_permissions,
            $dashboard_permissions,
            $settings_permissions
        );

        foreach ($additional_permissions as $additional_permission) {
            if (!$role->hasPermissionTo($additional_permission)) {
                $role->givePermissionTo($additional_permission);
            }
        }

        // Assign report, stock report, and dashboard permissions to Manager role
        $manager_additional_permissions = array_merge(
            $report_permissions,
            $stock_report_permissions,
            $dashboard_permissions,
            $settings_permissions,
            [
                'cash-reconciliation-show',
                'cash-reconciliation-approve',
                'cash-reconciliation-reverse-approval',
            ]
        );

        foreach ($manager_additional_permissions as $manager_permission) {
            if (!$manager_role->hasPermissionTo($manager_permission)) {
                $manager_role->givePermissionTo($manager_permission);
            }
        }

        // Assign cashier flow permissions
        $cashier_permissions = [
            'sale-create',
            'cash-reconciliation-list',
            'cash-reconciliation-create',
            'cash-reconciliation-edit',
        ];

        foreach ($cashier_permissions as $cashier_permission) {
            if (!$cashier_role->hasPermissionTo($cashier_permission)) {
                $cashier_role->givePermissionTo($cashier_permission);
            }
        }

        // Assign supervisor approval/view permissions
        $supervisor_permissions = [
            'cash-reconciliation-list',
            'cash-reconciliation-show',
            'cash-reconciliation-approve',
            'cash-reconciliation-reverse-approval',
            'user-audit-view',
            'user-filter-view',
        ];

        foreach ($supervisor_permissions as $supervisor_permission) {
            if (!$supervisor_role->hasPermissionTo($supervisor_permission)) {
                $supervisor_role->givePermissionTo($supervisor_permission);
            }
        }



    }
}

