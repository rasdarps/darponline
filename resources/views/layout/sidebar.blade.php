 <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->
                

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            {{-- transaction menu starts --}}
                            <li class="menu-title">Transctions</li>
                                <li><!--Main drop-->
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Manage Transactions</span>
                                    </a>

                                    <ul><!--drop ul-->

                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="ri-oil-fill"></i>
                                                <span>Purchase</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                <li><a href="{{ route('purchases.create') }}">Create</a></li>
                                                <li><a href="{{ route('purchases.index') }}">All Purchase</a></li>
                                            
                                            </ul>
                                        </li>

                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="ri-compass-2-fill"></i>
                                                <span>Sales</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                <li><a href="{{ route('invoices.create') }}">Create</a></li>
                                                <li><a href="{{ route('invoices.index') }}">All Sales</a></li>
                                                
                                            </ul>
                                        </li>
                                    </ul><!--drop ul ends-->    
                            </li><!--cover ends--> 
                         {{-- transaction menu ends --}}

                        {{-- setup menu starts --}}
                         <li class="menu-title">Setups</li>
                          <li><!--Main drop-->
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="fa fa-cog"></i>
                                <span>Manage Setups</span>
                            </a>
                            <ul><!--drop ul-->
                                 <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-building"></i>
                                        <span>Company</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('companies.create') }}">Create</a></li>
                                        <li><a href="{{ route('companies.index') }}">All Company</a></li>
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-users"></i>
                                        <span>Suppliers</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('suppliers.create') }}">Create</a></li>
                                        <li><a href="{{ route('suppliers.index') }}">All Supplier</a></li>   
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-building"></i>
                                        <span>Customers</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('customers.create') }}">Create</a></li>
                                        <li><a href="{{ route('customers.index') }}">All Customers</a></li>
                                        <li><a href="{{ route('credit.customer') }}">Credit Customers</a></li>

                                        <li><a href="{{ route('paid.customer') }}">Paid Customers</a></li>
                                        <li><a href="{{ route('customer.wise.report') }}">Customer Wise Report</a></li>
                                    
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-bars"></i>
                                        <span>Category</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('categories.create') }}">Create</a></li>
                                        <li><a href="{{ route('categories.index') }}">All Category</a></li>
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-book"></i>
                                        <span>Unit</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('units.create') }}">Create</a></li>
                                        <li><a href="{{ route('units.index') }}">All Unit</a></li>
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-truck"></i>
                                        <span>Product</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('products.create') }}">Create</a></li>
                                        <li><a href="{{ route('products.index') }}">All Product</a></li>
                                    
                                    </ul>
                                </li>

                                 <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-truck"></i>
                                        <span>Update Price</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('product-prices.index') }}">All Product Prices</a></li>
                                    
                                    </ul>
                                </li>
                            </ul><!--drop ul ends-->    
                        </li><!--cover ends--> 
                        {{-- setup menu ends --}}


                        {{-- setup user starts --}}
                        <li class="menu-title">User Setup</li>
                        <li><!--Main drop-->
                          <a href="javascript: void(0);" class="has-arrow waves-effect">
                              <i class="fa fa-users"></i>
                              <span>Manage Users</span>
                          </a>
                          <ul><!--drop ul-->

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Users</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('users.create') }}">Create</a></li>
                                        <li><a href="{{ route('users.index') }}">All Users</a></li>
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-id-card"></i>
                                        <span>Roles</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('roles.create') }}">Create</a></li>
                                        <li><a href="{{ route('roles.index') }}">All Roles</a></li>
                                        
                                    </ul>
                                </li>

                            </ul><!--drop ul ends-->    
                        </li><!--user li ends--> 
                        {{-- setup user ends    --}}

                            

                            <li class="menu-title">Reports</li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="fa-solid fa-chart-simple"></i>
                                    <span>Report</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('reports.profit') }}">Profit Report</a></li>
                                    <li><a href="{{ route('stock.report') }}">Stock Report</a></li>
                                    <li><a href="{{ route('stock.detailed_stock_report') }}">Detailed Stock Report</a></li>
                                    <li><a href="{{ route('stock.stock_value') }}">Stock Value Report</a></li>

                                    <li><a href="#">Backup</a></li>
                                </ul>
                            </li>

                            {{-- <li class="menu-title">System</li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="fa-solid fa-file"></i>
                                    <span>Backup</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="#">Backup</a>
                                        
                                    </li>
                                </ul>
                            </li> --}}
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>