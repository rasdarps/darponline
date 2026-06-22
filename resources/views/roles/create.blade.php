@extends('layout.main')
@section('title') {{ 'Create Roles' }} @endsection

@section('content')

    <!--Validator link-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header with-border">
                            <span class="card-title" style="font-size:20px;">Create | Role </span>

                            <a href="{{ route('roles.index') }}" style="float:right">
                                <button type="button" class="btn btn-primary modal_btn">View Roles</button>
                            </a>

                        </div>

                        <div class="card-body px-4 py-4">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
                                @csrf

                                <div class="row gx-3">
                                    <div class="col-lg-8 col-md-10 mb-3 mx-auto">
                                        <div class="form-group">
                                            <label for="name"><strong>Role Name: <span class="text-danger">*</span></strong></label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter role name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $permissionGroups = [
                                        'User and Role Management' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'user-') || str_contains($perm->name, 'role-');
                                        }),
                                        'Settings' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'unit-') || str_contains($perm->name, 'category-') || str_contains($perm->name, 'supplier-') 
                                            || str_contains($perm->name, 'product-')
                                            || str_contains($perm->name, 'customer-') || str_contains($perm->name, 'company-');
                                        }),
                                        'Transactions' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'sale-') || str_contains($perm->name, 'purchase-')
                                            || str_contains($perm->name, 'expense-') || str_contains($perm->name, '-adjustment-')
                                            || str_contains($perm->name, 'cash-');
                                        }),

                                        'Reports' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'report-') || str_contains($perm->name, 'stock-report-') || str_contains($perm->name, 'stock-ledger-report')
                                            || str_contains($perm->name, 'report-stock-adjustment') || str_contains($perm->name, 'stock-value-')
                                            || str_contains($perm->name, 'stock-product-trail-report') || str_contains($perm->name, 'stock-product-trail-report') 
                                            || str_contains($perm->name, 'report-stock-adjustment') || str_contains($perm->name, 'report-stock-adjustment') 
                                            || str_contains($perm->name, 'report-stock-adjustment') || str_contains($perm->name, 'report-stock-adjustment') 
                                            || str_contains($perm->name, 'report-stock-adjustment');
                                        }),
                                            
                                       
                                        'Other Permissions' => $permission->filter(function($perm) {
                                            return
                                                !str_contains($perm->name, 'user-') &&
                                                !str_contains($perm->name, 'role-') &&
                                                !str_contains($perm->name, 'unit-') &&
                                                !str_contains($perm->name, 'category-') &&
                                                !str_contains($perm->name, 'supplier-') &&
                                                !str_contains($perm->name, 'product-') &&
                                                !str_contains($perm->name, 'customer-') &&
                                                !str_contains($perm->name, 'purchase-') &&
                                                !str_contains($perm->name, 'expense-') &&
                                                !str_contains($perm->name, 'stock-') &&
                                                !str_contains($perm->name, 'report-') &&
                                                !str_contains($perm->name, 'company-') &&
                                                !str_contains($perm->name, 'cash-') &&
                                                !str_contains($perm->name, 'sale-') ;
                                               
                                        })
                                    ];
                                @endphp

                                <div class="row gx-3">
                                    <div class="col-lg-10 col-12 mb-3 mx-auto">
                                        <div class="form-group">
                                            <label><strong>Permissions: <span class="text-danger">*</span></strong></label>
                                            <small class="text-muted d-block mb-2">Select the permissions for this role</small>
                                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                                @if($groupPermissions->count() > 0)
                                                    <div class="mb-4 permission-group">
                                                        <h6 class="text-primary fw-bold mb-3">{{ $groupName }}</h6>
                                                        <div class="row">
                                                            @foreach($groupPermissions as $value)
                                                                <div class="col-lg-4 col-md-6 col-12 mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $value->id }}" id="perm{{ $value->id }}" {{ in_array($value->id, old('permission', [])) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="perm{{ $value->id }}">
                                                                            {{ $value->name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('styles')
<style>
    .permission-group {
        padding: 14px 16px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background-color: #fafbfc;
    }

    .permission-group .form-check {
        padding-left: 1.6rem;
    }
</style>
@endsection

