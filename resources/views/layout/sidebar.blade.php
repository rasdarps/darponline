 <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->
                

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>
                        @can('dashboard-view')
                            <li>
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                        @endcan
                            {{-- transaction menu starts --}}
                            @canany(['purchase-list', 'purchase-create', 'sale-list', 'sale-create', 'expense-list', 'expense-create', 'stock-adjustment-list', 'stock-adjustment-create', 'cash-reconciliation-list', 'cash-reconciliation-create', 'cash-reconciliation-show'])
                            <li class="menu-title">Transctions</li>
                                <li><!--Main drop-->
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Manage Transactions</span>
                                    </a>

                                    <ul><!--drop ul-->
                                     @canany(['purchase-list', 'purchase-create'])
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="ri-oil-fill"></i>
                                                <span>Purchase</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                @can('purchase-create')
                                                    <li><a href="{{ route('purchases.create') }}">Create</a></li>
                                                @endcan
                                                @can('purchase-list')
                                                    <li><a href="{{ route('purchases.index') }}">All Purchase</a></li>
                                                @endcan
                                            
                                            </ul>
                                        </li>
                                     @endcanany
                                     @canany(['sale-list', 'sale-create'])
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="ri-compass-2-fill"></i>
                                                <span>Sales</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                @can('sale-create')
                                                    <li><a href="{{ route('sales.create') }}">Create</a></li>
                                                @endcan
                                                @can('sale-list')
                                                    <li><a href="{{ route('sales.index') }}">All Sales</a></li>
                                                @endcan
                                                
                                            </ul>
                                        </li>
                                     @endcanany
                                     @canany(['expense-list', 'expense-create'])
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="ri-file-list-3-fill"></i>
                                                <span>Expenses</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                @can('expense-create')
                                                    <li><a href="{{ route('expense-vouchers.create') }}">Create</a></li>
                                                @endcan
                                                @can('expense-list')
                                                    <li><a href="{{ route('expense-vouchers.index') }}">All Expense </a></li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcanany

                                    @canany(['stock-adjustment-list', 'stock-adjustment-create'])
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="fa fa-cubes"></i>
                                                <span>Stock </span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                @can('stock-adjustment-create')
                                                    <li><a href="{{ route('stock-adjustments.create') }}">Create</a></li>
                                                @endcan
                                                @can('stock-adjustment-list')
                                                    <li><a href="{{ route('stock-adjustments.index') }}">All Stock Adjustments</a></li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcanany

                                    @canany(['cash-reconciliation-list', 'cash-reconciliation-create', 'cash-reconciliation-show'])
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="fa fa-money-bill-wave"></i>
                                                <span>Cash Reconcile</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                @can('cash-reconciliation-create')
                                                    <li><a href="{{ route('cash-reconciliations.create') }}">Create</a></li>
                                                @endcan
                                                @canany(['cash-reconciliation-list', 'cash-reconciliation-show'])
                                                    <li><a href="{{ route('cash-reconciliations.index') }}">All Reconciliations</a></li>
                                                @endcanany
                                            </ul>
                                        </li>
                                    @endcanany
                                    
                                </ul><!--drop ul ends-->    
                            </li><!--cover ends--> 
                            @endcanany
                         {{-- transaction menu ends --}}

                         @canany(['report-summary-ledger', 'report-profit', 'report-expense', 'report-stock-adjustment', 'stock-product-trail-report', 'stock-report', 'stock-value-report', 'stock-ledger-report', 'product-price-history-list'])
                         <li class="menu-title">Reports</li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="fa-solid fa-chart-simple"></i>
                                    <span>Report</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('report-summary-ledger')
                                        <li><a href="{{ route('reports.summary-ledger') }}">Summary Ledger</a></li>
                                    @endcan
                                    @can('report-profit')
                                        <li><a href="{{ route('reports.profit') }}">Profit Report</a></li>
                                    @endcan
                                    @can('stock-product-trail-report')
                                        <li><a href="{{ route('stock.product_trail_report') }}">Product-wise Stock Trail</a></li>
                                    @endcan
                                    @can('stock-report')
                                        <li><a href="{{ route('stock.report') }}">Summary Stock Report</a></li>
                                    @endcan
                                    @can('stock-value-report')
                                        <li><a href="{{ route('stock.stock_value') }}">Stock Value Report</a></li>
                                    @endcan
                                    @can('stock-ledger-report')
                                        <li><a href="{{ route('stock.ledger_report') }}">Stock Ledger Report</a></li>
                                    @endcan
                                    @can('report-stock-adjustment')
                                        <li><a href="{{ route('reports.stock-adjustment') }}">Stock Adjustment Report</a></li>
                                    @endcan           
                                    @can('report-expense')
                                        <li><a href="{{ route('reports.expense') }}">Expense Report</a></li>
                                    @endcan
                                    @can('product-price-history-list')
                                        <li><a href="{{ route('product-prices.product_price_history') }}">Product Price History</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- setup menu starts --}}
                         @canany(['company-list', 'company-create', 'supplier-list', 'supplier-create', 'customer-list', 'customer-create', 'category-list', 'category-create', 'unit-list', 'unit-create', 'expense-category-list', 'expense-category-create', 'product-list', 'product-create', 'product-price-list'])
                         <li class="menu-title">Setups</li>
                          <li><!--Main drop-->
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="fa fa-cog"></i>
                                <span>Manage Setups</span>
                            </a>
                            <ul><!--drop ul-->
                                @canany(['company-list', 'company-create'])
                                 <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-building"></i>
                                        <span>Company</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('company-create')
                                            <li><a href="{{ route('companies.create') }}">Create</a></li>
                                        @endcan
                                        @can('company-list')
                                            <li><a href="{{ route('companies.index') }}">All Company</a></li>
                                        @endcan
                                        
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['supplier-list', 'supplier-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-truck"></i>
                                        <span>Suppliers</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('supplier-create')
                                            <li><a href="{{ route('suppliers.create') }}">Create</a></li>
                                        @endcan
                                        @can('supplier-list')
                                            <li><a href="{{ route('suppliers.index') }}">All Supplier</a></li>
                                        @endcan   
                                        
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['customer-list', 'customer-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-users"></i>
                                        <span>Customers</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('customer-create')
                                            <li><a href="{{ route('customers.create') }}">Create</a></li>
                                        @endcan
                                        @can('customer-list')
                                            <li><a href="{{ route('customers.index') }}">All Customers</a></li>
                                            <li><a href="{{ route('credit.customer') }}">Credit Customers</a></li>
                                            <li><a href="{{ route('paid.customer') }}">Paid Customers</a></li>
                                        @endcan
                                    
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['category-list', 'category-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-bars"></i>
                                        <span>Category</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('category-create')
                                            <li><a href="{{ route('categories.create') }}">Create</a></li>
                                        @endcan
                                        @can('category-list')
                                            <li><a href="{{ route('categories.index') }}">All Category</a></li>
                                        @endcan
                                        
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['unit-list', 'unit-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-book"></i>
                                        <span>Unit</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('unit-create')
                                            <li><a href="{{ route('units.create') }}">Create</a></li>
                                        @endcan
                                        @can('unit-list')
                                            <li><a href="{{ route('units.index') }}">All Unit</a></li>
                                        @endcan
                                        
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['expense-category-list', 'expense-category-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-list"></i>
                                        <span>Expense Category</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('expense-category-create')
                                            <li><a href="{{ route('expense-categories.create') }}">Create</a></li>
                                        @endcan
                                        @can('expense-category-list')
                                            <li><a href="{{ route('expense-categories.index') }}">All Expense Categories</a></li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['product-list', 'product-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-truck"></i>
                                        <span>Product</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('product-create')
                                            <li><a href="{{ route('products.create') }}">Create</a></li>
                                        @endcan
                                        @can('product-list')
                                            <li><a href="{{ route('products.index') }}">All Product</a></li>
                                        @endcan
                                    
                                    </ul>
                                </li>
                                @endcanany

                                @can('product-price-list')
                                 <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-truck"></i>
                                        <span>Update Price</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('product-prices.index') }}">All Product Prices</a></li>
                                    
                                    </ul>
                                </li>
                                @endcan
                            </ul><!--drop ul ends-->    
                        </li><!--cover ends--> 
                        @endcanany
                        {{-- setup menu ends --}}


                        {{-- setup user starts --}}
                        @canany(['user-list', 'user-create', 'role-list', 'role-create'])
                        <li class="menu-title">User Setup</li>
                        <li><!--Main drop-->
                          <a href="javascript: void(0);" class="has-arrow waves-effect">
                              <i class="fa fa-users"></i>
                              <span>Manage Users</span>
                          </a>
                          <ul><!--drop ul-->

                                @canany(['user-list', 'user-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Users</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('user-create')
                                            <li><a href="{{ route('users.create') }}">Create</a></li>
                                        @endcan
                                        @can('user-list')
                                            <li><a href="{{ route('users.index') }}">All Users</a></li>
                                        @endcan
                                        
                                    </ul>
                                </li>
                                @endcanany

                                @canany(['role-list', 'role-create'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Roles</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @can('role-create')
                                            <li><a href="{{ route('roles.create') }}">Create</a></li>
                                        @endcan
                                        @can('role-list')
                                            <li><a href="{{ route('roles.index') }}">All Roles</a></li>
                                        @endcan
                                        
                                    </ul>
                                </li>
                                @endcanany
                            

                            </ul><!--drop ul ends-->    
                        </li><!--user li ends--> 
                        @endcanany
                        {{-- setup user ends    --}}
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
