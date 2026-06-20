<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\ExpenseCategoryController;
use App\Http\Controllers\Backend\ExpenseVoucherController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductPriceController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StockController;
use App\Http\Controllers\Backend\StockAdjustmentController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\ClientSide\OnlineShoppingController;
use App\Http\Controllers\ClientSide\ShopAuthController;
use App\Http\Controllers\ClientSide\ShopCartController;
use App\Http\Controllers\ClientSide\ShopCategoryController;
use App\Http\Controllers\ClientSide\ShopHomeController;
use App\Http\Controllers\ClientSide\ShopOrderController;
use App\Http\Controllers\ClientSide\ShopProductController;
use App\Http\Controllers\ClientSide\ShopProfileController;
use App\Http\Controllers\UploadController;
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
    return redirect()->route('shop.home');
});


Route::prefix('admin')->group(function () {

    Auth::routes();


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        Route::middleware('auth')->group(function(){

            //UPLOADS ROUTE

            Route::post('uploads', [UploadController::class, 'store'])->name('uploads');  


             // Admin All Route 
                Route::controller(AdminController::class)->group(function () {
                Route::get('/admin/logout', 'destroy')->name('admin.logout');
                Route::get('/admin/profile', 'Profile')->name('admin.profile');
                Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
                Route::post('/store/profile', 'StoreProfile')->name('store.profile');

                Route::get('/change/password', 'ChangePassword')->name('change.password');
                Route::post('/update/password', 'UpdatePassword')->name('update.password');
                
            });

            // Route::resource('/companies', CompanyController::class);
            // Route::resource('/roles', RoleController::class);
            // Route::resource('/users', UserController::class);


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
                Route::get('/credit/customer', 'CreditCustomer')->name('credit.customer');
                Route::get('/credit/customer/print/pdf', 'CreditCustomerPrintPdf')->name('credit.customer.print.pdf');

                Route::get('/customer/edit/invoice/{invoice_id}', 'CustomerEditInvoice')->name('customer.edit.invoice');
                Route::post('/customer/update/invoice/{invoice_id}', 'CustomerUpdateInvoice')->name('customer.update.invoice');

                Route::get('/customer/invoice/details/{invoice_id}', 'CustomerInvoiceDetails')->name('customer.invoice.details.pdf');

                Route::get('/paid/customer', 'PaidCustomer')->name('paid.customer');
                Route::get('/paid/customer/print/pdf', 'PaidCustomerPrintPdf')->name('paid.customer.print.pdf');

                Route::get('/customer/wise/report', 'CustomerWiseReport')->name('customer.wise.report');
                Route::get('/customer/wise/credit/report', 'CustomerWiseCreditReport')->name('customer.wise.credit.report');
                Route::get('/customer/wise/paid/report', 'CustomerWisePaidReport')->name('customer.wise.paid.report');
                
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
                Route::post('/update-website-status', [CategoryController::class, 'updateWebsiteStatus'])
                ->name('update-website-status');
            Route::post('/bulk-update-website', [CategoryController::class, 'bulkUpdateWebsiteStatus'])
                ->name('bulk-update-website');
    

            });


            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('/show/{id}', [ProductController::class, 'show'])->name('show');
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/store', [ProductController::class, 'store'])->name('store');
                Route::get('/edit/{encryptedId}', [ProductController::class, 'edit'])->name('edit');
                Route::patch('/update/{encryptedId}', [ProductController::class, 'update'])->name('update');
                Route::delete('/destroy/{encryptedId}', [ProductController::class, 'destroy'])->name('destroy');
                
                // ✅ New route for barcode lookup
                Route::post('/find-by-barcode', [ProductController::class, 'findByBarcode'])->name('findByBarcode');
                Route::post('/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/toggle-online', [ProductController::class, 'toggleOnline'])->name('toggle-online');
                Route::post('/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggle-featured');
                Route::delete('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
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
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [PurchaseController::class, 'create'])->name('create');
                Route::post('/store', [PurchaseController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [PurchaseController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [PurchaseController::class, 'destroy'])->name('destroy');
                Route::post('/cancel/{id}', [PurchaseController::class, 'cancel'])->name('cancel');
            
            });

            Route::controller(PurchaseController::class)->group(function () {

                Route::get('/print/purchase/list', 'PrintPurchaseList')->name('print.purchase.list');
                Route::get('/print/purchase/{id}', 'PrintPurchase')->name('print.purchase');

                Route::get('/daily/purchase/report', 'DailyPurchaseReport')->name('daily.purchase.report');
                Route::get('/daily/purchase/pdf', 'DailyPurchasePdf')->name('daily.purchase.pdf');
                
            });
        Route::prefix('companies')->name('companies.')->group(function () {
         route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::get('/create', [CompanyController::class, 'create'])->name('create');
            Route::post('/store', [CompanyController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [CompanyController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [CompanyController::class, 'destroy'])->name('destroy');

        });

        Route::resource('/roles', RoleController::class);
        Route::resource('/users', UserController::class);


            // Invoice All Route 
            Route::prefix('invoices')->name('invoices.')->group(function () {
                Route::get('/', [InvoiceController::class, 'index'])->name('index');
                Route::get('/create', [InvoiceController::class, 'create'])->name('create');
                Route::post('/store', [InvoiceController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [InvoiceController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [InvoiceController::class, 'update'])->name('update');
                Route::post('/cancel/{id}', [InvoiceController::class, 'cancel'])->name('cancel');
            });


            // Invoice All Route 
            Route::controller(InvoiceController::class)->group(function () {
                Route::get('/print/invoice/list', 'PrintInvoiceList')->name('print.invoice.list');
                Route::get('/print/invoice/{id}', 'PrintInvoice')->name('print.invoice');

                Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
                Route::get('/daily/invoice/pdf', 'DailyInvoicePdf')->name('daily.invoice.pdf');
                
                
            });

            //Reports Routes
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/profit', [ReportController::class, 'profitReport'])->name('profit');
                Route::get('/profit/summary', [ReportController::class, 'profitSummary'])->name('profit.summary');
                Route::get('/summary-ledger', [ReportController::class, 'summaryLedger'])->name('summary-ledger');
                Route::get('/expense', [ReportController::class, 'expenseReport'])->name('expense');
                Route::get('/stock-adjustment', [ReportController::class, 'stockAdjustmentReport'])->name('stock-adjustment');

            });





            // Stock All Route 
            Route::controller(StockController::class)->group(function () {
                
                Route::get('/stock/report', 'StockReport')->name('stock.report');
                Route::get('/stock/ledger/report', 'StockLedgerReport')->name('stock.ledger_report');
                Route::get('/stock/product-trail/report', 'ProductWiseStockTrail')->name('stock.product_trail_report');
                Route::get('/stock_value', 'StockValue')->name('stock.stock_value');


                Route::get('/stock/report/pdf', 'StockReportPdf')->name('stock.report.pdf'); 
                Route::get('/product/wise/pdf', 'ProductWisePdf')->name('product.wise.pdf');
                Route::get('/stock/category/wise', 'StockCategoryWise')->name('stock.category.wise');
                Route::get('/category/wise/pdf', 'CategoryWisePdf')->name('category.wise.pdf');
                
            
            });



    }); // End Group Middleware


        // This route will trigger the backup process when accessed
        // Route::get('/backup', function () {
        //     // Call the backup:run command
        //     Artisan::call('backup:run');
        //     // Redirect the user back to the previous page
        //     return redirect()->back();
        // })->name('backup');


  
});

  // Default All Route 
  Route::controller(DefaultController::class)->group(function () {
    Route::get('/get-category', 'GetCategory')->name('get-category'); 
    Route::get('/get-product', 'GetProduct')->name('get-product'); 
    Route::get('/get-unit', 'GetUnit')->name('get-unit'); 
    Route::get('/check-product', 'GetStock')->name('check-product-stock'); 
    
});

 


//Online Shopping

Route::prefix('shop')->name('shop.')->group(function () {

    Route::middleware('guest:customer')->group(function () {


        Route::get('login', [ShopAuthController::class, 'login_index'])->name('login_page');

        Route::get('/register', [ShopAuthController::class, 'register_index'])->name('registration_page');
        Route::post('login', [ShopAuthController::class, 'login'])->name('login');
        Route::post('/register', [ShopAuthController::class, 'register_post'])->name('register');
        Route::get('/forgot-password', [ShopAuthController::class, 'showForgotForm'])->name('password.request');
        Route::post('/forgot-password', [ShopAuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [ShopAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ShopAuthController::class, 'resetPassword'])->name('password.update');
    });


            Route::middleware('auth:customer')->group(function () {

                Route::post('logout', [ShopAuthController::class, 'logout'])->name('logout');
                // Profile Management
                Route::get('/profile', [ShopProfileController::class, 'index'])->name('profile');
                Route::put('/profile/update', [ShopProfileController::class, 'update'])->name('profile.update');
                Route::post('/profile/change-password', [ShopProfileController::class, 'changePassword'])->name('profile.change-password');


                // Orders
                Route::get('/orders', [ShopOrderController::class, 'index'])->name('orders');
                Route::get('/orders/{id}', [ShopOrderController::class, 'show'])->name('orders.show');
                Route::post('/orders/{id}/cancel', [ShopOrderController::class, 'cancel'])->name('orders.cancel');

                // // Checkout
                // Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
                // Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
                        
                // // Profile
                // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
                // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
                //  // Addresses
                // Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
                // Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
                // Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
                // Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');

                Route::get('/wishlist', [OnlineShoppingController::class, 'wishlist_page'])->name('wishlist_page');
                
        
            });

                // Wishlist (Public - Session Based)
                Route::get('/wishlist', [ShopProductController::class, 'wishlist'])->name('wishlist');
             Route::post('/wishlist/add/{id}', [ShopProductController::class, 'addToWishlist'])->name('wishlist.add');
             Route::delete('/wishlist/remove/{id}', [ShopProductController::class, 'removeFromWishlist'])->name('wishlist.remove');

             // Compare (Public - Session Based)
            // Route::prefix('compare')->name('compare.')->group(function () {
            //     Route::get('/', [CompareController::class, 'index'])->name('index');
            //     Route::post('/add', [CompareController::class, 'add'])->name('add');
            //     Route::delete('/remove', [CompareController::class, 'remove'])->name('remove');
            // });

            // Shop/Product Routes (Public - No Login Required)

            Route::get('/product/{slug}/quick-view', [OnlineShoppingController::class, 'quickView'])->name('product.quick-view');
            Route::get('/product/{slug}', [OnlineShoppingController::class, 'productDetails'])->name('product.details');
            Route::get('/products', [OnlineShoppingController::class, 'products'])->name('products');
            Route::get('/product', [OnlineShoppingController::class, 'product_page'])->name('product_page');

            
          // Cart (session-based, works without login)
          Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [ShopCartController::class, 'index'])->name('index');
            Route::post('/add', [ShopCartController::class, 'add'])->name('add');
            Route::put('/update', [ShopCartController::class, 'update'])->name('update');
            Route::delete('/remove', [ShopCartController::class, 'remove'])->name('remove');
            Route::post('/coupon/apply', [ShopCartController::class, 'applyCoupon'])->name('coupon.apply');
            Route::delete('/coupon/remove', [ShopCartController::class, 'removeCoupon'])->name('coupon.remove');
            Route::get('/count', [ShopCartController::class, 'count'])->name('count');
            Route::get('/total', [ShopCartController::class, 'getTotal'])->name('total');
            Route::get('/items', [ShopCartController::class, 'getItems'])->name('items');
            Route::post('/clear', [ShopCartController::class, 'clear'])->name('clear');
        });
        // Public routes (both guest and authenticated can access)
        Route::get('/', [OnlineShoppingController::class, 'index'])->name('home');
        Route::get('/about', [ShopHomeController::class, 'about'])->name('about');
        Route::get('/contact', [ShopHomeController::class, 'contact'])->name('contact');
        Route::post('/contact', [ShopHomeController::class, 'submitContact'])->name('contact.submit');

        // Collections/Categories (Public)

        Route::get('/collections', [OnlineShoppingController::class, 'collection_page'])->name('collection_page');
        Route::get('/collections/{slug}', [ShopCategoryController::class, 'show'])->name('collections.show');
        Route::get('/collections/all', [ShopCategoryController::class, 'getAllCategories'])->name('collections.all');
});

 