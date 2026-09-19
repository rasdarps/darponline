@extends('layout.main')
@section('title') {{ 'Create Roles' }} @endsection

@section('content')

    <!--Validator link-->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card role-form-card shadow-sm border-0">
                        <div class="card-header role-form-header with-border">
                            <div>
                                <span class="card-title role-form-title">Create Role</span>
                                <p class="role-form-subtitle mb-0">Define a new access profile and assign permissions for the team member.</p>
                            </div>

                            <a href="{{ route('roles.index') }}" class="btn btn-outline-primary role-header-btn">
                                View Roles
                            </a>
                        </div>

                        <div class="card-body role-form-body px-4 py-4">
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

                                @php
                                    $groupDescriptions = [
                                        'User and Role Management' => 'Manage staff, role assignments, and access control settings.',
                                        'Sales' => 'Create and manage sales transactions and related workflows.',
                                        'Purchases' => 'Create and manage supplier purchases and purchase follow-up tasks.',
                                        'Stocks' => 'Handle stock adjustments and inventory movement operations.',
                                        'Expenses' => 'Create and manage expense records and expense categories.',
                                        'Reconciliation' => 'Handle cash reconciliation workflows and approvals.',
                                        'Reports' => 'View reporting screens and analytical summaries.',
                                        'Products' => 'Manage product records and product pricing tools.',
                                        'Suppliers' => 'Manage supplier records and supplier information.',
                                        'Customers' => 'Manage customer records and related account data.',
                                        'Company and Settings' => 'Control company details and application settings used across the POS.',
                                        'Dashboard' => 'Access the main dashboard and summary widgets.',
                                        'Other Permissions' => 'Any permissions that do not fall into the main groups above.',
                                    ];

                                    $permissionDescriptions = [
                                        'user-list' => 'View the user list and review user accounts.',
                                        'user-create' => 'Create new user accounts.',
                                        'user-edit' => 'Edit existing user details and access.',
                                        'user-delete' => 'Remove user accounts from the system.',
                                        'user-audit-view' => 'View user audit activity and account history.',
                                        'user-filter-view' => 'Access user filters and user search tools.',
                                        'role-list' => 'View roles and inspect assigned permissions.',
                                        'role-create' => 'Create new roles for access control.',
                                        'role-edit' => 'Update role names and permissions.',
                                        'role-delete' => 'Delete roles that are no longer needed.',
                                        'stock-report' => 'Access stock report features.',
                                        'stock-product-trail-report' => 'View stock product trail reports.',
                                        'unit-list' => 'View Unit records.',
                                        'unit-create' => 'Create Unit records.',
                                        'unit-edit' => 'Edit Unit records.',
                                        'unit-delete' => 'Delete Unit records.',
                                        'category-list' => 'View Category records.',
                                        'category-create' => 'Create Category records.',
                                        'category-edit' => 'Edit Category records.',
                                        'category-delete' => 'Delete Category records.',
                                        'stock-adjustment-list' => 'View stock adjustment records.',
                                        'stock-adjustment-create' => 'Create stock adjustment records.',
                                        'stock-adjustment-edit' => 'Edit stock adjustment records.',
                                        'stock-adjustment-delete' => 'Delete stock adjustment records.',
                                        'stock_adjustment-print' => 'Print stock adjustment records.',
                                        'expense-list' => 'View expense records.',
                                        'expense-create' => 'Create expense records.',
                                        'expense-edit' => 'Edit expense records.',
                                        'expense-delete' => 'Delete expense records.',
                                        'expense-category-list' => 'View expense category records.',
                                        'expense-category-create' => 'Create expense category records.',
                                        'expense-category-edit' => 'Edit expense category records.',
                                        'expense-category-delete' => 'Delete expense category records.',
                                    ];

                                    $permissionDescription = function (string $permissionName) use ($permissionDescriptions): string {
                                        foreach ($permissionDescriptions as $key => $text) {
                                            if (str_contains($permissionName, $key)) {
                                                return $text;
                                            }
                                        }

                                        $parts = explode('-', $permissionName);
                                        $module = ucfirst($parts[0] ?? 'Permission');
                                        $action = $parts[1] ?? 'manage';

                                        return match ($action) {
                                            'list', 'index', 'view' => "View {$module} records.",
                                            'create' => "Create {$module} records.",
                                            'edit', 'update' => "Edit {$module} records.",
                                            'delete', 'remove' => "Delete {$module} records.",
                                            default => "Access {$module} features.",
                                        };
                                    };

                                    $permissionGroups = [
                                        'User and Role Management' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'user-') || str_contains($perm->name, 'role-');
                                        }),
                                        'Sales' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'sale-');
                                        }),
                                        'Purchases' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'purchase-');
                                        }),
                                        'Stocks' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'stock-adjustment-') || str_contains($perm->name, 'stock_adjustment-');
                                        }),
                                        'Expenses' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'expense-') || str_contains($perm->name, 'expense-category-');
                                        }),
                                        'Reconciliation' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'cash-reconciliation-');
                                        }),
                                        'Reports' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'report-') || str_contains($perm->name, 'stock-report') || str_contains($perm->name, 'stock-report-') || str_contains($perm->name, 'stock-ledger-report')
                                            || str_contains($perm->name, 'report-stock-adjustment') || str_contains($perm->name, 'stock-value-')
                                            || str_contains($perm->name, 'stock-product-trail-report') || str_contains($perm->name, 'view-purchase-edit-trail');
                                        }),
                                        'Products' => $permission->filter(function($perm) {
                                            return str_starts_with($perm->name, 'product-') || str_starts_with($perm->name, 'product-price-')
                                                || str_starts_with($perm->name, 'unit-') || str_starts_with($perm->name, 'category-');
                                        }),
                                        'Suppliers' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'supplier-');
                                        }),
                                        'Customers' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'customer-');
                                        }),
                                        'Company and Settings' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'company-')
                                            || str_contains($perm->name, 'settings-manage') || str_contains($perm->name, 'daily-transaction-email-send');
                                        }),
                                        'Dashboard' => $permission->filter(function($perm) {
                                            return str_contains($perm->name, 'dashboard-view');
                                        }),
                                        'Other Permissions' => $permission->filter(function($perm) {
                                            return
                                                !str_contains($perm->name, 'user-') &&
                                                !str_contains($perm->name, 'role-') &&
                                                !str_contains($perm->name, 'sale-') &&
                                                !str_contains($perm->name, 'purchase-') &&
                                                !str_contains($perm->name, 'stock-adjustment-') &&
                                                !str_contains($perm->name, 'stock_adjustment-') &&
                                                !str_contains($perm->name, 'expense-') &&
                                                !str_contains($perm->name, 'expense-category-') &&
                                                !str_contains($perm->name, 'cash-reconciliation-') &&
                                                !str_contains($perm->name, 'report-') &&
                                                !str_contains($perm->name, 'stock-report') &&
                                                !str_contains($perm->name, 'stock-report-') &&
                                                !str_contains($perm->name, 'stock-ledger-report') &&
                                                !str_contains($perm->name, 'stock-value-') &&
                                                !str_contains($perm->name, 'stock-product-trail-report') &&
                                                !str_contains($perm->name, 'view-purchase-edit-trail') &&
                                                !str_contains($perm->name, 'product-') &&
                                                !str_contains($perm->name, 'product-price-') &&
                                                !str_contains($perm->name, 'unit-') &&
                                                !str_contains($perm->name, 'category-') &&
                                                !str_contains($perm->name, 'supplier-') &&
                                                !str_contains($perm->name, 'customer-') &&
                                                !str_contains($perm->name, 'company-') &&
                                                !str_contains($perm->name, 'settings-manage') &&
                                                !str_contains($perm->name, 'daily-transaction-email-send') &&
                                                !str_contains($perm->name, 'dashboard-view');
                                        })
                                    ];
                                @endphp

                                <div class="role-form-intro mb-4">
                                    <div class="role-form-intro-icon">R</div>
                                    <div>
                                        <h5 class="mb-1">Role basics</h5>
                                        <p class="mb-0 text-muted">Give the role a clear name, then choose the areas this role can access.</p>
                                    </div>
                                </div>

                                <div class="row gx-3">
                                    <div class="col-lg-8 col-md-10 mb-4 mx-auto">
                                        <div class="form-group role-field-card">
                                            <label for="name" class="role-field-label"><strong>Role Name <span class="text-danger">*</span></strong></label>
                                            <small class="text-muted d-block mb-2">This label helps staff quickly understand what access level the role represents.</small>
                                            <input type="text" id="name" name="name" class="form-control role-input" placeholder="Enter role name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-3">
                                    <div class="col-12 mb-4 mx-auto">
                                        <div class="form-group role-field-card">
                                            <label class="role-field-label"><strong>Permissions <span class="text-danger">*</span></strong></label>
                                            <small class="text-muted d-block mb-3">Tick only the actions this role should be allowed to perform. Permissions are grouped to make scanning easier.</small>

                                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                                @if($groupPermissions->count() > 0)
                                                    <div class="mb-4 permission-group">
                                                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                                                            <div>
                                                                <h6 class="permission-group-title mb-1">{{ $groupName }}</h6>
                                                                <p class="permission-group-description mb-0">{{ $groupDescriptions[$groupName] ?? 'Choose the access options that belong to this category.' }}</p>
                                                            </div>
                                                            <span class="permission-group-badge">{{ $groupPermissions->count() }} permissions</span>
                                                        </div>

                                                        <div class="row">
                                                            @foreach($groupPermissions as $value)
                                                                <div class="col-lg-4 col-md-6 col-12 mb-2">
                                                                    <div class="form-check permission-check">
                                                                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $value->id }}" id="perm{{ $value->id }}" {{ in_array($value->id, old('permission', [])) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="perm{{ $value->id }}">
                                                                            {{ $value->name }}
                                                                        </label>
                                                                        <small class="text-muted d-block mt-1">{{ $permissionDescription($value->name) }}</small>
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

                                <div class="row mt-2">
                                    <div class="col-12 text-center d-flex flex-wrap justify-content-center gap-2">
                                        <a href="{{ route('roles.index') }}" class="btn btn-light px-4">Cancel</a>
                                        <button type="submit" class="btn btn-success px-4">Save Role</button>
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
    .role-form-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .role-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
        border-bottom: 1px solid #e6edf7;
    }

    .role-form-title {
        display: block;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f3b68;
    }

    .role-form-subtitle {
        color: #64748b;
        font-size: 0.95rem;
    }

    .role-header-btn {
        white-space: nowrap;
        border-radius: 999px;
        padding-inline: 1.1rem;
    }

    .role-form-intro {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.15rem;
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #e9eef5;
    }

    .role-form-intro-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #0d6efd, #4f8cff);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.18);
    }

    .role-field-card {
        padding: 1rem 1.15rem;
        border-radius: 14px;
        border: 1px solid #e9eef5;
        background: #fff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.03);
    }

    .role-field-label {
        display: block;
        margin-bottom: 0.25rem;
    }

    .role-input {
        min-height: 48px;
        border-radius: 12px;
    }

    .permission-group {
        padding: 1rem 1rem 0.5rem;
        border: 1px solid #e9eef5;
        border-radius: 14px;
        background: #fbfcfe;
    }

    .permission-group-title {
        font-weight: 700;
        color: #1f3b68;
    }

    .permission-group-description {
        color: #6b7280;
        font-size: 0.92rem;
    }

    .permission-group-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: #eaf2ff;
        color: #1f5fbf;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .permission-check {
        min-height: 44px;
        padding: 0.7rem 0.75rem;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        background: #fff;
    }

    .permission-check .form-check-label {
        word-break: break-word;
    }

    .permission-check .form-check-input {
        margin-top: 0.2rem;
    }

    .permission-check:hover {
        border-color: #cfe0ff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
    }

    @media (max-width: 767.98px) {
        .role-form-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection
