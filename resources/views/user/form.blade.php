@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create New User
                        <a href="{{route('user.index')}}" class="btn btn-sm btn-primary" style="float: right">Back
                            to List</a>
                    </div>

                    <div class="card-body">
                        @include('layouts.errors')

                        @if(!isset($user))
                            {!! Form::open(['route' => 'user.store']) !!}
                        @else
                            {!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'put'])  !!}
                        @endif

                        <div class="form-group">
                            {!! Form::label('name', 'Name') !!}
                            {!! Form::text('name', NULL, ['placeholder' => 'Enter your name', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', NULL, ['placeholder' => 'Enter you email', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('password', 'Password') !!}
                            {!! Form::password('password', ['placeholder' => 'Enter password', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('password_confirmation', 'Confirm Password') !!}
                            {!! Form::password('password_confirmation', ['placeholder' => 'Confirm your password', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('', 'Roles') !!}

                            <div class="form-group">
                            @foreach($roles as $role)
                                <label>{!! Form::checkbox('roles[]', $role->id, $userRoles->search($role->id) !== false); !!} {{ $role->name }}</label> &nbsp;  &nbsp;
                                @endforeach
                            </div>

                        </div>

                        {!! Form::submit(isset($user) ? 'Update' : 'Save', ['class' => 'btn btn-success btn-sm']) !!}
                        {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
