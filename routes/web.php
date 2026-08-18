<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CashReconciliationController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DailyTransactionEmailController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ExpenseCategoryController;
use App\Http\Controllers\Backend\ExpenseVoucherController;
use App\Http\Controllers\Backend\SaleController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductPriceController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StockController;
use App\Http\Controllers\Backend\StockAdjustmentController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



//Route::get('/dashboard', function () {
   // return view('admin.index');
//})->middleware(['auth'])->name('dashboard');

//require __DIR__.'/auth.php';

Route::get('/admin/home', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin/login');
});


Route::prefix('admin')->group(function () {

    Auth::routes();


        Route::middleware(['auth', 'force.password.change'])->group(function(){
          
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/insight', [DashboardController::class, 'insight'])->name('insight');


             // Admin All Route 
                Route::controller(AdminController::class)->group(function () {
                Route::get('/admin/logout', 'destroy')->name('admin.logout');
                Route::get('/admin/profile', 'Profile')->name('admin.profile');
                Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
                Route::post('/store/profile', 'StoreProfile')->name('store.profile');

                Route::get('/change/password', 'ChangePassword')->name('change.password');
                Route::post('/update/password', 'UpdatePassword')->name('update.password');
                
            });


            Route::prefix('suppliers')->name('suppliers.')->group(function () {
                Route::get('/', [SupplierController::class, 'index'])->name('index');
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [SupplierController::class, 'create'])->name('create');
                Route::post('/store', [SupplierController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [SupplierController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [SupplierController::class, 'destroy'])->name('destroy');
                

            });


            // Customer All Route 
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::get('/', [CustomerController::class, 'index'])->name('index');
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [CustomerController::class, 'create'])->name('create');
                Route::post('/store', [CustomerController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [CustomerController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [CustomerController::class, 'destroy'])->name('destroy');
                

            });
            Route::controller(CustomerController::class)->group(function () {
                
            });


            Route::prefix('units')->name('units.')->group(function () {
                Route::get('/', [UnitController::class, 'index'])->name('index');
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [UnitController::class, 'create'])->name('create');
                Route::post('/store', [UnitController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [UnitController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [UnitController::class, 'destroy'])->name('destroy');
                

            });

            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('index');
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [CategoryController::class, 'create'])->name('create');
                Route::post('/store', [CategoryController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [CategoryController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');

            });


            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('/show/{id}', [ProductController::class, 'show'])->name('show');
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/store', [ProductController::class, 'store'])->name('store');
                Route::post('/import', [ProductController::class, 'import'])->name('import');
                Route::get('/edit/{encryptedId}', [ProductController::class, 'edit'])->name('edit');
                Route::patch('/update/{encryptedId}', [ProductController::class, 'update'])->name('update');
                Route::delete('/destroy/{encryptedId}', [ProductController::class, 'destroy'])->name('destroy');
                
                // Barcode lookup supports both scanner-driven GET and form POST callers
                Route::match(['get', 'post'], '/find-by-barcode', [ProductController::class, 'findByBarcode'])->name('findByBarcode');
                Route::get('/print-barcodes', [ProductController::class, 'printBarcodes'])->name('print-barcodes');
                Route::post('/update-stock', [ProductController::class, 'updateStock'])->name('update-stock');
                Route::get('/export', [ProductController::class, 'export'])->name('export');

            });

            Route::prefix('expense-vouchers')->name('expense-vouchers.')->group(function () {
                Route::get('/', [ExpenseVoucherController::class, 'index'])->name('index');
                Route::get('/create', [ExpenseVoucherController::class, 'create'])->name('create');
                Route::get('/show/{id}', [ExpenseVoucherController::class, 'show'])->name('show');
                Route::get('/print/{id}', [ExpenseVoucherController::class, 'print'])->name('print');
                Route::post('/store', [ExpenseVoucherController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [ExpenseVoucherController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [ExpenseVoucherController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [ExpenseVoucherController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('expense-categories')->name('expense-categories.')->group(function () {
                Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
                Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('create');
                Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [ExpenseCategoryController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [ExpenseCategoryController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('stock-adjustments')->name('stock-adjustments.')->group(function () {
                Route::get('/', [StockAdjustmentController::class, 'index'])->name('index');
                Route::get('/create', [StockAdjustmentController::class, 'create'])->name('create');
                Route::post('/store', [StockAdjustmentController::class, 'store'])->name('store');
                Route::get('/show/{id}', [StockAdjustmentController::class, 'show'])->name('show');
                Route::get('/print/{id}', [StockAdjustmentController::class, 'print'])->name('print');
                Route::get('/edit/{id}', [StockAdjustmentController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [StockAdjustmentController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [StockAdjustmentController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('cash-reconciliations')->name('cash-reconciliations.')->group(function () {
                Route::get('/', [CashReconciliationController::class, 'index'])->name('index');
                Route::get('/create', [CashReconciliationController::class, 'create'])->name('create');
                Route::get('/expected', [CashReconciliationController::class, 'expected'])->name('expected');
                Route::post('/store', [CashReconciliationController::class, 'store'])->name('store');
                Route::get('/show/{id}', [CashReconciliationController::class, 'show'])->name('show');
                Route::get('/edit/{id}', [CashReconciliationController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [CashReconciliationController::class, 'update'])->name('update');
                Route::post('/approve/{id}', [CashReconciliationController::class, 'approve'])->name('approve');
                Route::post('/reverse-approval/{id}', [CashReconciliationController::class, 'reverseApproval'])->name('reverse-approval');
            });

            //Product Price routes
            Route::prefix('product-prices')->name('product-prices.')->group(function () {
                Route::get('/', [ProductPriceController::class, 'index'])->name('index');
                Route::get('/edit/{encryptedId}', [ProductPriceController::class, 'edit'])->name('edit');
                Route::patch('/update/{encryptedId}', [ProductPriceController::class, 'update'])->name('update');
                Route::delete('/destroy/{encryptedId}', [ProductPriceController::class, 'destroy'])->name('destroy');

                //price change history
                Route::get('/product_price_history', [ProductPriceController::class, 'ProductPriceHistory'])->name('product_price_history');

            });


            
            // Purchase All Route 
            Route::prefix('purchases')->name('purchases.')->group(function () {
                Route::get('/', [PurchaseController::class, 'index'])->name('index');
                Route::get('/create', [PurchaseController::class, 'create'])->name('create');
                Route::post('/store', [PurchaseController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [PurchaseController::class, 'update'])->name('update');
                Route::post('/cancel/{id}', [PurchaseController::class, 'cancel'])->name('cancel');
            });

            Route::controller(PurchaseController::class)->group(function () {

                Route::get('/print/purchase/list', 'PrintPurchaseList')->name('print.purchase.list');
                Route::get('/print/purchase/{id}', 'PrintPurchase')->name('print.purchase');

                
            });
        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::get('/create', [CompanyController::class, 'create'])->name('create');
            Route::post('/store', [CompanyController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [CompanyController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [CompanyController::class, 'destroy'])->name('destroy');

        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::patch('/update', [SettingsController::class, 'update'])->name('update');
        });

        Route::prefix('daily-transaction-email')->name('daily-transaction-email.')->group(function () {
            Route::get('/', [DailyTransactionEmailController::class, 'index'])->name('index');
            Route::get('/logs', [DailyTransactionEmailController::class, 'logs'])->name('logs');
            Route::post('/send', [DailyTransactionEmailController::class, 'sendManual'])->name('send');
            Route::get('/status', [DailyTransactionEmailController::class, 'getStatus'])->name('status');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/show/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');

        });

         Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/show/{id}', [RoleController::class, 'show'])->where('id', '.*')->name('show');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/store', [RoleController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [RoleController::class, 'edit'])->where('id', '.*')->name('edit');
                Route::patch('/update/{id}', [RoleController::class, 'update'])->where('id', '.*')->name('update');
                Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->where('id', '.*')->name('destroy');

        });


            // Sale All Route 
            Route::prefix('sales')->name('sales.')->group(function () {
                Route::get('/', [SaleController::class, 'index'])->name('index');
                Route::get('/create', [SaleController::class, 'create'])->name('create');
                Route::get('/daily-total-sales', [SaleController::class, 'dailyTotalSales'])->name('daily-total-sales');
                Route::post('/store', [SaleController::class, 'store'])->name('store');
                Route::post('/cancel/{id}', [SaleController::class, 'cancel'])->name('cancel');
            });


            // Sale All Route 
            Route::controller(SaleController::class)->group(function () {
                Route::get('/print/sale/list', 'printSaleList')->name('print.sale.list');
                Route::get('/print/sale/{id}', 'printSale')->name('print.sale');

                
                
            });

            //Reports Routes
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/profit', [ReportController::class, 'profitReport'])->name('profit');
                Route::get('/profit/summary', [ReportController::class, 'profitSummary'])->name('profit.summary');
                Route::get('/summary-ledger', [ReportController::class, 'summaryLedger'])->name('summary-ledger');
                Route::get('/expense', [ReportController::class, 'expenseReport'])->name('expense');
                Route::get('/stock-adjustment', [ReportController::class, 'stockAdjustmentReport'])->name('stock-adjustment');
                Route::get('/purchase-edit-trail', [ReportController::class, 'purchaseAuditReport'])->name('purchase-edit-trail');

            });





            // Stock All Route 
            Route::controller(StockController::class)->group(function () {
                
                Route::get('/stock/report', 'StockReport')->name('stock.report');
                Route::get('/stock/ledger/report', 'StockLedgerReport')->name('stock.ledger_report');
                Route::get('/stock/product-trail/report', 'ProductWiseStockTrail')->name('stock.product_trail_report');
                Route::get('/stock_value', 'StockValue')->name('stock.stock_value');


                
            
            });



    }); // End Group Middleware
  
});
