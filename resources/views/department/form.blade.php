@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create New Department
                        <a href="{{route('department.index')}}" class="btn btn-sm btn-primary" style="float: right">Back
                            to List</a>
                    </div>

                    <div class="card-body">
                        @include('layouts.errors')

                        @if(!isset($department))
                        {!! Form::open(['route' => 'department.store']) !!}
                        @else
                        {!! Form::model($department, ['route' => ['department.update', $department->id], 'method' => 'put'])  !!}
                        @endif

                        <div class="form-group">
                            {!! Form::label('name', 'Name') !!}
                            {!! Form::text('name', NULL, ['placeholder' => 'Enter department name', 'class' => 'form-control']) !!}
                        </div>

                        {!! Form::submit(isset($department) ? 'Update' : 'Save', ['class' => 'btn btn-success btn-sm']) !!}
                        {!! Form::button('Reset', ['type' => 'reset', 'class' => 'btn btn-danger btn-sm']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
