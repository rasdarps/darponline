  <header id="page-topbar">
                <div class="navbar-header" style="background-color:#0f172a; position: relative;">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            {{-- <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo_cart.png">
                                    <img src="{{ asset('backend/assets/images/logo_cart.png') }}" alt="logo_cart.png" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('backend/assets/images/logo_cart.png') }}" alt="logo-dark" height="20">
                                </span>
                            </a> --}}

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                {{-- <span class="logo_cart.png">
                                    <img src="{{ asset('backend/assets/images/logo_cart.png') }}" alt="logo_cart.png-light" height="22">
                                </span> --}}
                                <span class="logo-lg">
                                    <img src="{{ asset('images/rict_logo.png') }}" alt="logo-light" height="60">
                                </span>
                            </a>
                        </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
        <i class="ri-menu-2-line align-middle"></i>
    </button>

    <div class="header-quick-actions d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="btn quick-action-btn header-item waves-effect" title="Dashboard">
            <i class="ri-home-4-line align-middle"></i>
        </a>
        <a href="{{ route('invoices.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Sale">
            <i class="ri-shopping-cart-2-line align-middle"></i>
        </a>
        <a href="{{ route('purchases.create') }}" class="btn quick-action-btn header-item waves-effect" title="Create Purchase">
            <i class="ri-add-circle-line align-middle"></i>
        </a>
        <a href="{{ route('invoices.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Sales">
            <i class="ri-file-list-3-line align-middle"></i>
        </a>
        <a href="{{ route('purchases.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Purchases">
            <i class="ri-archive-line align-middle"></i>
        </a>
        <a href="{{ route('product-prices.index') }}" class="btn quick-action-btn header-item waves-effect" title="View Price">
            <i class="ri-price-tag-3-line align-middle"></i>
        </a>
        <a href="{{ route('stock.report') }}" class="btn quick-action-btn header-item waves-effect" title="View Stock Report">
            <i class="ri-bar-chart-box-line align-middle"></i>
        </a>
        <a href="{{ route('admin.logout') }}" class="btn quick-action-btn header-item waves-effect" title="Logout">
            <i class="ri-logout-box-r-line align-middle"></i>
        </a>
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
    @endphp

    <div class="dropdown d-inline-block user-dropdown">
        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="rounded-circle header-profile-user" src="{{ (!empty($adminData->profile_image))? url('upload/admin_images/'.$adminData->profile_image):url('upload/rict_logo.png') }}"
                alt="Header Avatar">
            <span class="d-none d-xl-inline-block ms-1">{{ $adminData->name }}</span>
            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
            <a class="dropdown-item" href="{{ route('change.password') }}"><i class="ri-wallet-2-line align-middle me-1"></i> Change Password</a>
            <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
            <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
        </div>
    </div>

                        
            
                    </div>
                </div>

            </header>