<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Department;

class DepartmentController extends Controller
{

    public function index()
    {
        $departments = Department::paginate(15);

        return response()->json($departments);
    }

    public function show(Department $department)
    {
        return response()->json($department);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:departments',
        ]);

        $department       = new Department();
        $department->name = $request->name;
        $department->save();

        return response()->json($department);

    }

    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'name' => 'required|unique:departments,name,' . $department->id . ',id',
        ]);

        $department->name = $request->name;
        $department->save();

        return response()->json($department);

    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json($department);
    }
}
