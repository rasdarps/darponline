<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StockController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\UnitController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\ClientSide\Auth\ClientLoginController;
use App\Http\Controllers\ClientSide\Auth\ClientRegistrationController;
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

            Route::resource('/companies', CompanyController::class);
            Route::resource('/roles', RoleController::class);
            Route::resource('/users', UserController::class);


            Route::prefix('suppliers')->name('suppliers.')->group(function () {
                Route::get('/', [SupplierController::class, 'index'])->name('index');
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [SupplierController::class, 'create'])->name('create');
                Route::post('/store', [SupplierController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [SupplierController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [UnitController::class, 'destroy'])->name('destroy');
                

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
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/store', [ProductController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [ProductController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [ProductController::class, 'destroy'])->name('destroy');
                
                // ✅ New route for barcode lookup
                Route::post('/find-by-barcode', [ProductController::class, 'findByBarcode'])->name('findByBarcode');

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
                // Route to get the next purchase number
                Route::get('/next-number', [PurchaseController::class, 'nextNumber'])->name('nextNumber');

            });

            Route::controller(PurchaseController::class)->group(function () {
                // Route::get('/purchase/all', 'PurchaseAll')->name('purchase.all'); 
                // Route::get('/purchase/add', 'PurchaseAdd')->name('purchase.add');
                // Route::post('/purchase/store', 'PurchaseStore')->name('purchase.store');
                // Route::get('/purchase/delete/{id}', 'PurchaseDelete')->name('purchase.delete');

                // Route::get('/purchase/pending/list', 'PendingList')->name('purchase.pending.list');
                // Route::get('/purchase/approved/list', 'ApprovedList')->name('purchase.approved.list');

                //Route::get('/purchase/pending', 'PurchasePending')->name('purchase.pending');
                // Route::get('/purchase/approve/{id}', 'PurchaseApprove')->name('purchase.approve');
                // Route::post('purchase//approval/store/{id}', 'ApprovalStore')->name('purchase.approval.store');

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
                // Route::get('/show/{id}', [CategoryController::class, 'show'])->name('show');
                Route::get('/create', [InvoiceController::class, 'create'])->name('create');
                Route::post('/store', [InvoiceController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [InvoiceController::class, 'edit'])->name('edit');
                Route::patch('/update/{id}', [InvoiceController::class, 'update'])->name('update');
                Route::delete('/destroy/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
                // Route to get the next invoice number
                Route::get('/next-number', [InvoiceController::class, 'nextNumber'])->name('nextNumber');

            });


            // Invoice All Route 
            Route::controller(InvoiceController::class)->group(function () {
                // Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all'); 
                // Route::get('/invoice/add', 'invoiceAdd')->name('invoice.add');
                // Route::post('/invoice/store', 'InvoiceStore')->name('invoice.store');

                // Route::get('/invoice/pending/list', 'PendingList')->name('invoice.pending.list');
                // Route::get('/invoice/approved/list', 'ApprovedList')->name('invoice.approved.list');

                Route::get('/invoice/delete/{id}', 'InvoiceDelete')->name('invoice.delete');
                // Route::get('/invoice/approve/{id}', 'InvoiceApprove')->name('invoice.approve');
                // Route::post('/approval/store/{id}', 'ApprovalStore')->name('approval.store');
                

                Route::get('/print/invoice/list', 'PrintInvoiceList')->name('print.invoice.list');
                Route::get('/print/invoice/{id}', 'PrintInvoice')->name('print.invoice');

                Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
                Route::get('/daily/invoice/pdf', 'DailyInvoicePdf')->name('daily.invoice.pdf');
                
                
            });





            // Stock All Route 
            Route::controller(StockController::class)->group(function () {
                Route::get('/stock/report', 'StockReport')->name('stock.report');
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


           // Wishlist
        Route::get('/wishlist', [ShopProductController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist/add/{id}', [ShopProductController::class, 'addToWishlist'])->name('wishlist.add');
        Route::delete('/wishlist/remove/{id}', [ShopProductController::class, 'removeFromWishlist'])->name('wishlist.remove');

        Route::get('/collections', [OnlineShoppingController::class, 'collection_page'])->name('collection_page');
        Route::get('/product', [OnlineShoppingController::class, 'product_page'])->name('product_page');
        Route::get('/whislist', [OnlineShoppingController::class, 'wishlist_page'])->name('wishlist_page');
            // Cart (session-based, works without login)
            Route::get('/cart', [ShopCartController::class, 'index'])->name('cart');
            Route::post('/cart/add/{id}', [ShopCartController::class, 'add'])->name('cart.add');
            Route::put('/cart/update/{id}', [ShopCartController::class, 'update'])->name('cart.update');
            Route::delete('/cart/remove/{id}', [ShopCartController::class, 'remove'])->name('cart.remove');
            Route::post('/cart/coupon/apply', [ShopCartController::class, 'applyCoupon'])->name('cart.coupon.apply');
            Route::delete('/cart/coupon/remove', [ShopCartController::class, 'removeCoupon'])->name('cart.coupon.remove');
 
    });
          // Cart (session-based, works without login)
          Route::get('/cart', [ShopCartController::class, 'index'])->name('cart');
          Route::post('/cart/add/{id}', [ShopCartController::class, 'add'])->name('cart.add');
          Route::put('/cart/update/{id}', [ShopCartController::class, 'update'])->name('cart.update');
          Route::delete('/cart/remove/{id}', [ShopCartController::class, 'remove'])->name('cart.remove');
          Route::post('/cart/coupon/apply', [ShopCartController::class, 'applyCoupon'])->name('cart.coupon.apply');
          Route::delete('/cart/coupon/remove', [ShopCartController::class, 'removeCoupon'])->name('cart.coupon.remove');

        // Public routes (both guest and authenticated can access)
        Route::get('/', [OnlineShoppingController::class, 'index'])->name('home');
        Route::get('/about', [ShopHomeController::class, 'about'])->name('about');
        Route::get('/contact', [ShopHomeController::class, 'contact'])->name('contact');
        Route::post('/contact', [ShopHomeController::class, 'submitContact'])->name('contact.submit');

        // collections
        Route::get('/collections{slug}', [ShopCategoryController::class, 'show'])->name('collections.show');
        Route::get('/collections/all', [ShopCategoryController::class, 'getAllCategories'])->name('collections.all');
});

 