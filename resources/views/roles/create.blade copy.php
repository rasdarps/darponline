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

                            <hr>
                        </div>

                        <div class="card-body">
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

                            <form action="{{ route('roles.store') }}" method="POST">
                                @csrf

                                <div class="row col-md-10 mx-auto">
                                    <div class="col-md-12 my-3">
                                        <div class="form-group">
                                            <label for="name"><strong>Role Name: <span class="text-danger">*</span></strong></label>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                                        </div>
                                    </div>

                                    @php
                                        $grouped = [];
                                        foreach ($permission as $perm) {
                                            if (preg_match('/^(.*)-(list|create|edit|delete)$/', $perm->name, $matches)) {
                                                $resource = ucfirst($matches[1]);
                                                $action = $matches[2];
                                                $grouped[$resource][$action] = $perm;
                                            }
                                        }
                                        $actions = ['list', 'create', 'edit', 'delete'];
                                    @endphp

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><strong>Permission:</strong></label>
                                            <div class="table-responsive mt-2">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Resource</th>
                                                            <th>List</th>
                                                            <th>Create</th>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($grouped as $resource => $perms)
                                                            <tr>
                                                                <td><strong>{{ $resource }}</strong></td>
                                                                @foreach ($actions as $action)
                                                                    <td>
                                                                        @if (isset($perms[$action]))
                                                                            <label class="mb-0">
                                                                                <input type="checkbox" name="permission[]" value="{{ $perms[$action]->id }}" {{ in_array($perms[$action]->id, old('permission', [])) ? 'checked' : '' }}>
                                                                                {{ ucfirst($action) }}
                                                                            </label>
                                                                        @endif
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
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
