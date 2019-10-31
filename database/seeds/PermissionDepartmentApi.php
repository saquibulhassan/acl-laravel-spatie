<?php

use App\Enums\AclEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionDepartmentApi extends Seeder
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
        $read = Permission::create(['name' => 'api.department.index', 'module' => 'Department API', 'permission' => AclEnum::Read]);
        Permission::create(['name' => 'api.department.show', 'module' => 'Department API', 'parent_permission' => $read->id]);

        Permission::create(['name' => 'api.department.store', 'module' => 'Department API', 'permission' => AclEnum::Create]);
        Permission::create(['name' => 'api.department.update', 'module' => 'Department API', 'permission' => AclEnum::Update]);
        Permission::create(['name' => 'api.department.destroy', 'module' => 'Department API', 'permission' => AclEnum::Delete]);
    }
}
