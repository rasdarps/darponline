<style>
    @media (max-width: 991.98px) {
        #page-topbar .navbar-header {
            height: auto;
            min-height: 70px;
            align-items: flex-start;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        #page-topbar .navbar-header > .d-flex:first-child {
            flex: 1 1 auto;
            min-width: 0;
            flex-wrap: wrap;
            align-items: flex-start;
            row-gap: 6px;
        }

        #page-topbar #vertical-menu-btn {
            margin-left: 0 !important;
            margin-right: 8px !important;
        }

        #page-topbar .header-quick-actions {
            flex: 1 1 100%;
            display: flex;
            flex-wrap: wrap;
            align-items: center !important;
            gap: 6px;
            margin-top: 4px;
            padding-right: 8px;
        }

        #page-topbar .header-quick-actions .quick-action-btn {
            height: 38px;
            min-width: 38px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
    }

    @media (max-width: 575.98px) {
        #page-topbar .navbar-header {
            min-height: 150px;
            padding-top: 12px;
            padding-bottom: 14px;
        }

        #page-topbar .header-quick-actions {
            row-gap: 8px;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        #page-topbar .header-quick-actions .quick-action-btn {
            height: 34px;
            min-width: 34px;
            padding: 0 8px;
        }
    }
</style>  
  <header id="page-topbar">
                <div class="navbar-header" style="background-color:#0f766e; position: relative;">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            {{-- <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo_cart.png">
                                    <img src="{{ asset('images/logo_cart.png') }}" alt="logo_cart.png" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('images/logo_cart.png') }}" alt="logo-dark" height="20">
                                </span>
                            </a> --}}

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                {{-- <span class="logo_cart.png">
                                    <img src="{{ asset('images/logo_cart.png') }}" alt="logo_cart.png-light" height="22">
                                </span> --}}
                                <span class="logo-lg">
                                    <img src="{{ asset('images/rict_logo.png') }}" alt="logo-light" height="60" style="margin-top: 5px;">
                                </span>
                            </a>
                        </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn" style="margin-left: -65px; margin-right: 18px;">
                    <i class="ri-menu-2-line align-middle"></i>
                </button>

    <div class="header-quick-actions d-flex align-items-center">
        @can('dashboard-view')
            <a href="{{ route('dashboard') }}" class="btn quick-action-btn header-item waves-effect" title="Dashboard">
                <i class="ri-home-4-line align-middle"></i>
            </a>
        @endcan
        <!-- Purchases -->
        @can('purchase-create')
            <a href="{{ route('purchases.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Purchase">
                <i class="ri-add-circle-line align-middle"></i>
            </a>
        @endcan
        @can('purchase-list')
            <a href="{{ route('purchases.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Purchases">
                <i class="ri-file-list-3-line align-middle"></i>
            </a>
        @endcan
        <!-- Sales -->
        @can('sale-create')
            <a href="{{ route('sales.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Sale">
                <i class="ri-shopping-cart-2-line align-middle"></i>
            </a>
        @endcan
        @can('sale-list')
            <a href="{{ route('sales.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Sales">
                <i class="ri-file-list-3-line align-middle"></i>
            </a>
        @endcan
        <!-- Products -->
        @can('product-create')
            <a href="{{ route('products.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Product">
                <i class="fa fa-apple-whole align-middle"></i>
            </a>
        @endcan
        @can('product-list')
            <a href="{{ route('products.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Products">
                <i class="ri-file-list-3-line align-middle"></i>
            </a>
        @endcan
        <!-- Categories -->
        {{-- @can('category-create')
            <a href="{{ route('categories.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Category">
                <i class="ri-layout-grid-line align-middle"></i>
            </a>
        @endcan
        @can('category-list')
            <a href="{{ route('categories.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Categories">
                <i class="ri-file-list-3-line align-middle"></i>
            </a>
        @endcan --}}
        <!-- Units -->
        {{-- @can('unit-create')
            <a href="{{ route('units.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Unit">
                <i class="ri-ruler-line align-middle"></i>
            </a>
        @endcan
        @can('unit-list')
            <a href="{{ route('units.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Units">
                <i class="ri-file-list-3-line align-middle"></i>
            </a>
        @endcan --}}
        <!-- Reports -->
        @can('report-summary-ledger')
            <a href="{{ route('reports.summary-ledger') }}" class="btn quick-action-btn header-item waves-effect" title="Summary Ledger">
                <i class="ri-money-dollar-circle-line align-middle"></i>
            </a>
        @endcan
        @can('report-profit')
            <a href="{{ route('reports.profit') }}" class="btn quick-action-btn header-item waves-effect" title="Profit Report">
                <i class="ri-money-dollar-circle-line align-middle"></i>
            </a>
        @endcan
        @can('report-expense')
            <a href="{{ route('reports.expense') }}" class="btn quick-action-btn header-item waves-effect" title="Expense Report">
                <i class="ri-money-dollar-circle-line align-middle"></i>
            </a>
        @endcan
        @can('stock-report')
            <a href="{{ route('stock.report') }}" class="btn quick-action-btn header-item waves-effect" title="Stock Report">
                <i class="ri-bar-chart-box-line align-middle"></i>
            </a>
        @endcan
        @can('stock-ledger-report')
            <a href="{{ route('stock.ledger_report') }}" class="btn quick-action-btn header-item waves-effect" title="Stock Ledger Report">
                <i class="ri-bar-chart-box-line align-middle"></i>
            </a>
        @endcan
        @can('report-stock-adjustment')
            <a href="{{ route('reports.stock-adjustment') }}" class="btn quick-action-btn header-item waves-effect" title="Stock Adjustment Report">
                <i class="ri-bar-chart-box-line align-middle"></i>
            </a>
        @endcan
        @can('product-price-history-list')
            <a href="{{ route('product-prices.product_price_history') }}" class="btn quick-action-btn header-item waves-effect" title="Product Price History">
                <i class="ri-history-line align-middle"></i>
            </a>
        @endcan
        @can('stock-value-report')
            <a href="{{ route('stock.stock_value') }}" class="btn quick-action-btn header-item waves-effect" title="Stock Value">
                <i class="ri-coin-line align-middle"></i>
            </a>
        @endcan
        @can('stock-product-trail-report')
            <a href="{{ route('stock.product_trail_report') }}" class="btn quick-action-btn header-item waves-effect" title="Product Trail Report">
                <i class="ri-bar-chart-box-line align-middle"></i>
            </a>
        @endcan
        <!-- Settings & Logout -->
        @can('settings-manage')
            <a href="{{ route('settings.index') }}" class="btn quick-action-btn header-item waves-effect" title="Settings">
                <i class="ri-settings-3-line align-middle"></i>
            </a>
        @endcan
        {{-- <a href="{{ route('admin.logout') }}" class="btn quick-action-btn header-item waves-effect" title="Logout">
            <i class="ri-logout-box-r-line align-middle"></i>
        </a> --}}
    </div>

    <!-- App Search-->
    {{-- <form class="app-search d-none d-lg-block">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search...">
            <span class="ri-search-line"></span>
        </div>
    </form> --}}
    

    
</div>

<div class="d-flex">



    <div class="dropdown d-none d-lg-inline-block ms-1">
        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
            <i class="ri-fullscreen-line"></i>
        </button>
    </div>

    @php
    $id = Auth::user()->id;
    $adminData = App\Models\User::find($id);
    $storageDefault = public_path('storage/profile_images/rict_logo.png');
    $headerProfileImage = file_exists($storageDefault)
        ? asset('storage/profile_images/rict_logo.png')
        : asset('images/rict_logo.png');
    if (!empty($adminData->profile_image)) {
        $storagePath = str_starts_with($adminData->profile_image, 'profile_images/')
            ? $adminData->profile_image
            : 'profile_images/' . ltrim($adminData->profile_image, '/');
        $storageFile = public_path('storage/' . $storagePath);
        if (file_exists($storageFile)) {
            $headerProfileImage = asset('storage/' . $storagePath);
        }
    }
    @endphp

    <div class="dropdown d-inline-block user-dropdown">
        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="rounded-circle header-profile-user" src="{{ $headerProfileImage }}"
                alt="Header Avatar">
            <span class="d-none d-xl-inline-block ms-1">{{ $adminData->username }}</span>
            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
            <a class="dropdown-item" href="{{ route('change.password') }}"><i class="ri-wallet-2-line align-middle me-1"></i> Change Password</a>
            <a class="dropdown-item d-block" href="{{ route('settings.index') }}"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
            <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
        </div>
    </div>

                        
            
                    </div>
                </div>

            </header>
