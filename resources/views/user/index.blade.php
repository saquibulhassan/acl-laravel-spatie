@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        User
                        <a href="{{route('user.create')}}" class="btn btn-sm btn-primary" style="float: right">Create
                            New User</a>
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
                                <th class="text-center">Email</th>
                                <th width="135" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $k => $user)
                                <tr>
                                    <td class="text-center">{{ $users->firstItem() + $k }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                           class="btn btn-sm btn-primary">Edit</a>

                                        {!! Form::open(['route' => ['user.destroy', $user->id], 'method' => 'delete', 'class' => 'delete-form']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']); !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
