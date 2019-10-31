<?php

use App\Enums\AclEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $read = Permission::create(['name' => 'department.index', 'module' => 'Department', 'permission' => AclEnum::Read]);
        Permission::create(['name' => 'department.show', 'module' => 'Department', 'parent_permission' => $read->id]);

        $create = Permission::create(['name' => 'department.create', 'module' => 'Department', 'permission' => AclEnum::Create]);
        Permission::create(['name' => 'department.store', 'module' => 'Department', 'parent_permission' => $create->id]);

        $update = Permission::create(['name' => 'department.edit', 'module' => 'Department', 'permission' => AclEnum::Update]);
        Permission::create(['name' => 'department.update', 'module' => 'Department', 'parent_permission' => $update->id]);

        Permission::create(['name' => 'department.destroy', 'module' => 'Department', 'permission' => AclEnum::Delete]);
    }
}
