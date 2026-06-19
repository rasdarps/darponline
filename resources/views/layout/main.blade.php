<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <!--<title>Dashboard | Admin & Dashboard </title>-->
        <title>Sales and Inventory System | @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Best Sales and Inventory System" name="description" />
        <meta content="Themesdesign" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

        <!-- jquery.vectormap css -->
        <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!--select 2-->
        <link href="{{ asset('backend/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- DataTables -->
        {{-- <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  
        <link rel="stylesheet" type="text/css" href="{{asset('datatables/Buttons-2.2.3/css/buttons.dataTables.min.css')}}"/>
        <link href="{{asset('datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"> --}}
        <!-- Yajra DataTables CSS -->
       <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
       <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
       <link rel="stylesheet" href="{{ asset('DataTables/datatables.css') }}" />  

        {{-- notiflix --}}
        <link rel="stylesheet" href=  "{{ asset('notiflix/notiflix-3.2.8.min.css') }}"/>

        <!--fontawesome-->
        <link href="{{asset('fontawesome/css/all.css')}}" rel="stylesheet">


        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/css/bootstrap4.5.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link href="{{ asset('uppy/dist/uppy.min.css') }}" rel="stylesheet">

        @yield('styles')
        @stack('styles')
        <style>
            .filter-summary {
                background: #f8f9fa;
                padding: 12px 15px;
                border-radius: 4px;
                margin-bottom: 20px;
                border-left: 4px solid #007bff;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
            }

            .filter-summary i {
                color: #007bff;
                font-size: 16px;
            }

            .filter-summary .badge {
                padding: 5px 10px;
                font-size: 12px;
                font-weight: 500;
                border-radius: 3px;
            }

            .filter-summary .btn-link {
                padding: 0 5px;
                font-size: 12px;
                text-decoration: none;
                color: #dc3545;
            }

            .filter-summary .btn-link:hover {
                text-decoration: underline;
            }
            /* Uppy Dashboard Style */
            [data-uppy-drag-drop-supported=true] .uppy-Dashboard-AddFiles {
                border: 1px dashed #0f9cf3 !important;
                border-radius: 3px;
                height: calc(100% - 14px);
                margin: 7px;
            }

            .uppy-Dashboard-inner {
                background-color: #e2ebf1 !important;
                /* border: 1px solid #0f9cf3; */
                border-radius: 5px;
                max-height: 100%;
                max-width: 100%;
                outline: none;
                position: relative;
            }

            /* Yajra DataTables Global Styles */
            table.dataTable thead th {
                border-bottom: 2px solid #dee2e6 !important;
                border-top: none !important;
                border-left: none !important;
                border-right: none !important;
                background-color: #f8f9fa !important;
                font-weight: 600 !important;
                padding: 12px 8px !important;
            }
             table.dataTable tbody tr {
                border-bottom: 1px solid #dee2e6 !important;
                border-top: none !important;
                border-left: none !important;
                border-right: none !important;
            }

            table.dataTable tbody td {
                padding: 10px 8px !important;
                border: none !important;
                border-bottom: 1px solid #dee2e6 !important;
            }
            
            table.dataTable tbody tr:nth-child(odd) {
                background-color: #ffffff !important;
            }
            
            table.dataTable tbody tr:nth-child(even) {
                background-color: #f8f9fa !important;
            }
            
            table.dataTable tbody tr:hover {
                background-color: #e9ecef !important;
            }
            
            /* Remove all outer table borders */
            table.dataTable {
                border: none !important;
                border-collapse: collapse !important;
            }
            
            table.dataTable tbody tr:hover {
                background-color: #e9ecef !important;
            }

            .header-quick-actions {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                gap: 10px;
                z-index: 2;
                flex-wrap: nowrap;
            }

            .header-quick-actions .quick-action-btn {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                color: #ffffff;
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.28);
                transition: all 0.2s ease;
                padding: 0;
            }

            .header-quick-actions .quick-action-btn:hover {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.26);
                transform: translateY(-1px);
            }

            @media (max-width: 1199.98px) {
                .header-quick-actions {
                    left: 54%;
                    gap: 6px;
                }

                .header-quick-actions .quick-action-btn {
                    width: 36px;
                    height: 36px;
                    font-size: 18px;
                }
            }

            @media (max-width: 767.98px) {
                .header-quick-actions {
                    left: auto;
                    right: 58px;
                    transform: translateY(-50%);
                    gap: 4px;
                    max-width: 58vw;
                    overflow-x: auto;
                    scrollbar-width: none;
                }

                .header-quick-actions::-webkit-scrollbar {
                    display: none;
                }

                .header-quick-actions .quick-action-btn {
                    width: 32px;
                    height: 32px;
                    font-size: 16px;
                    flex: 0 0 auto;
                }
            }
        </style>
        
    </head>

    {{-- <body data-topbar="dark" oncontextmenu="return false;"> --}}
    <body data-topbar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
          @include('layout.header')

            <!-- ========== Left Sidebar Start ========== -->
           @include('layout.sidebar')
            <!-- Left Sidebar End -->

            
            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                
                @yield('content')
                    <!-- End Page-content -->
                

                @include('layout.footer')
                
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        
    @include('layout.scripts')

@yield('scripts')
@stack('scripts')
</body>

</html>