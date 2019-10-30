@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Department
                        @can('department.create')
                            <a href="{{route('department.create')}}" class="btn btn-sm btn-primary"
                               style="float: right">Create New Department</a>
                        @endcan
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
                                @canany(['department.edit', 'department.destroy'])
                                    <th width="135" class="text-center">Action</th>
                                @endcanany
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($departments as $k => $department)
                                <tr>
                                    <td class="text-center">{{ $departments->firstItem() + $k }}</td>
                                    <td>{{ $department->name }}</td>
                                    @canany(['department.edit', 'department.destroy'])
                                        <td class="text-center">
                                            @can('department.edit')
                                                <a href="{{ route('department.edit', $department->id) }}"
                                                   class="btn btn-sm btn-primary">Edit</a>
                                            @endcan

                                            @can('department.destroy')
                                                {!! Form::open(['route' => ['department.destroy', $department->id], 'method' => 'delete', 'class' => 'delete-form']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']); !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
