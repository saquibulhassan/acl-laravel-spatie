<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## ACL With Laravel 6.x & Spatie 3.2

We have extend Spatie to develop this ACL. 
With Spatie we can control access permission using bellow approach.
- Using Middleware : We have set middleware with every route. That is time consuming for development.
- Using construct function in every controller. It is also boring.

So We made this solutions to minimize the above issue. We made a Middleware that's can verify every request. So now we don't have to repeat ourself again & again to develop ACL. 

This approach also come with one limitations. That is every route must have a name. Without name of route this solutions will not work.

We also made a php artisan command to create seed for permission. But this will work only with resource controller. If you need any other method except default method then you have to modify the seed file yourself. 

``` bash
php artisan SeedFileName ResourceControllerRouteName ModuleName 
```

For Example : 
``` bash
php artisan PermissionDepartmentSeeder department Department 
```

After running this command we will get the bellow file in seed directory.

```php
use App\Enums\SystemPermission;
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
        $read = Permission::create(['name' => 'department.index', 'module' => 'Department', 'permission' => SystemPermission::Read]);
        Permission::create(['name' => 'department.show', 'module' => 'Department', 'parent_permission' => $read->id]);

        $create = Permission::create(['name' => 'department.create', 'module' => 'Department', 'permission' => SystemPermission::Create]);
        Permission::create(['name' => 'department.store', 'module' => 'Department', 'parent_permission' => $create->id]);

        $update = Permission::create(['name' => 'department.edit', 'module' => 'Department', 'permission' => SystemPermission::Update]);
        Permission::create(['name' => 'department.update', 'module' => 'Department', 'parent_permission' => $update->id]);

        Permission::create(['name' => 'department.destroy', 'module' => 'Department', 'permission' => SystemPermission::Delete]);
    }
}
```

We can modify this file as per as our requirements.


### Middleware
As previously discussed we made a middleware called "AccessControlList" to boost our development. This middleware must be present in "$routeMiddleware" group of "Kernel.php" file in app/Http.
```php
'acl' => \App\Http\Middleware\AccessControlList::class,
```

### Route
Now we have to set this middleware in our route.
```php
Route::group(['middleware' => ['acl']], function () {
    Route::resource('/department', 'DepartmentController');
});
```

