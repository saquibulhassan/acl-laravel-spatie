@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Role
                        <a href="{{route('role.create')}}" class="btn btn-sm btn-primary" style="float: right">Create
                            New Role</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th width="50" class="text-center">SL</th>
                                <th class="text-center">Name</th>
                                <th width="135" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $k => $role)
                                <tr>
                                    <td class="text-center">{{ $roles->firstItem() + $k }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('role.edit', $role->id) }}"
                                           class="btn btn-sm btn-primary">Edit</a>

                                        {!! Form::open(['route' => ['role.destroy', $role->id], 'method' => 'delete', 'class' => 'delete-form']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']); !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
