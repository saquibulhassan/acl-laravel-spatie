@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create New Role
                        <a href="{{route('role.index')}}" class="btn btn-sm btn-primary" style="float: right">Back
                            to List</a>
                    </div>

                    <div class="card-body">
                        @include('layouts.errors')

                        @if(!isset($role))
                            {!! Form::open(['route' => 'role.store']) !!}
                        @else
                            {!! Form::model($role, ['route' => ['role.update', $role->id], 'method' => 'put'])  !!}
                        @endif

                        <div class="form-group">
                            {!! Form::label('name', 'Name') !!}
                            {!! Form::text('name', NULL, ['placeholder' => 'Enter role name', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('', 'Permissions') !!}

                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Module</th>
                                    <th>Permission</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i = 1 @endphp
                                @foreach($permissionModules as $k => $permissions)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $k }}</td>
                                    <td>
                                        @foreach($permissions as $permission)
                                            <label>{!! Form::checkbox('permissions[]', $permission->id, $rolePermissions->search($permission->id) !== false); !!} {{ $permission->permission }}</label>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::submit(isset($role) ? 'Update' : 'Save', ['class' => 'btn btn-success btn-sm']) !!}
                        {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
