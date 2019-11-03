<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## ACL With Laravel 6.x & Spatie 3.2

We have extend Spatie to develop this ACL. 
With Spatie we can control access permission using bellow approach.
- Using Middleware : We have set middleware with every route. That is time consuming for development.
- Using construct function in every controller. It is also boring.

So We made this implementation to minimize the above issue. We made a Middleware that's can verify every request. So now we don't have to repeat ourself again & again to develop ACL. 

This implementation also come with one limitations. That is every route must have a name. Without name of route this implementation will not work.

### Installation
This implementation has 3 dependency. To install all of them just run the bellow command
 ``` bash
 composer require bensampo/laravel-enum
 composer require spatie/laravel-permission
 ```
Now add tymon/jwt-auth as dependency in composer.json file.
```json
"tymon/jwt-auth": "^1.0"
```
Then run bellow command to install tymon jwt auth package.
 ``` bash
 composer update
 ```

### Configuration
Step 1 : update service provider array in config/app.php
```php
  'providers' => [
  
    ...
  
    Spatie\Permission\PermissionServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
  ]
```
Step 2 : Publish package config & migrations.
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Step 3 : Run bellow php artisan command
```bash
php artisan migrate
php artisan jwt:secret
```

Step 5 : Run bellow php artisan command to create class of enumerous data. This will create a file in app/Enums directory.
```bash
pa make:enum AclEnum
```

Step 6 : Edit AclEnum file & put the bellow code there.
```php
<?php
    namespace App\Enums;
    
    use BenSampo\Enum\Enum;
    
    final class AclEnum extends Enum
    {
        const Read = 'Read';
        const Create = 'Create';
        const Update = 'Update';
        const Delete = 'Delete';
        const Preview = 'Preview';
        const Download = 'Download';
    }
```

Step 7 : Now create a custom command. It will help us to generate seed for permission. Run the bellow command & edit the file as bellow.
```bash
php artisan make:command PermissionSeederMakeCommand
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputArgument;

class PermissionSeederMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:permission:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class Spatie permission';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Permission seeder';

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct($files);

        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
        $this->composer->dumpAutoloads();

        $this->info('Please do not forget to add seed class to DatabaseSeeder');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $isApi = $this->option('api');

        if($isApi) {
            return __DIR__.'/Stubs/ApiPermissionSeeder.stub';
        } else {
            return __DIR__.'/Stubs/PermissionSeeder.stub';
        }
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel->databasePath().'/seeds/'.$name.'.php';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return $name;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the seed class'],
            ['route', InputArgument::REQUIRED, 'The resource route name'],
            ['module', InputArgument::REQUIRED, 'The module name'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     *
     */
    protected function getOptions()
    {
        return [
            ['api', NULL, NULL, 'Use for resource api']
        ];
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace('DummyClass', $class, $stub);

        $route = $this->argument('route');
        $stub = str_replace('DummyRoute', $route, $stub);

        $module = $this->argument('module');
        return str_replace('DummyModule', $module, $stub);
    }

}
```

Step 8 : Step 8 will required two more file inside app/Console/Commands/Stubs folder.

- ApiPermissionSeeder.stub
```text
<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\AclEnum;

class DummyClass extends Seeder
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
        $read = Permission::create(['name' => 'DummyRoute.index', 'module' => 'DummyModule', 'permission' => AclEnum::Read]);
        Permission::create(['name' => 'DummyRoute.show', 'module' => 'DummyModule', 'parent_permission' => $read->id]);

        Permission::create(['name' => 'DummyRoute.store', 'module' => 'DummyModule', 'permission' => AclEnum::Create]);
        Permission::create(['name' => 'DummyRoute.update', 'module' => 'DummyModule', 'permission' => AclEnum::Update]);
        Permission::create(['name' => 'DummyRoute.destroy', 'module' => 'DummyModule', 'permission' => AclEnum::Delete]);
    }
}
```
- PermissionSeeder.stub
```text
<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\AclEnum;

class DummyClass extends Seeder
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
        $read = Permission::create(['name' => 'DummyRoute.index', 'module' => 'DummyModule', 'permission' => AclEnum::Read]);
        Permission::create(['name' => 'DummyRoute.show', 'module' => 'DummyModule', 'parent_permission' => $read->id]);

        $create = Permission::create(['name' => 'DummyRoute.create', 'module' => 'DummyModule', 'permission' => AclEnum::Create]);
        Permission::create(['name' => 'DummyRoute.store', 'module' => 'DummyModule', 'parent_permission' => $create->id]);

        $update = Permission::create(['name' => 'DummyRoute.edit', 'module' => 'DummyModule', 'permission' => AclEnum::Update]);
        Permission::create(['name' => 'DummyRoute.update', 'module' => 'DummyModule', 'parent_permission' => $update->id]);

        Permission::create(['name' => 'DummyRoute.destroy', 'module' => 'DummyModule', 'permission' => AclEnum::Delete]);
    }
}
```

Step 9 : Now crate a middleware with bellow command & edit the the file as bellow
```bash
php artisan make:middleware AccessControlList
```

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AccessControlList
{
    protected $ignore = [];
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if (Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user has this //permission
        {
            return $next($request);
        }*/

        /*
         * Do not forget to set exception for route name missing
         * */

        $routeName = $request->route()->action['as'];

        if (!Auth::user()->hasPermissionTo($routeName)) {
            if (!$request->expectsJson()) {
                abort('401');
            } else {
                return response()->json(['error' => 'Sorry! You are not permitted to access this page'], 401);
            }
        }

        return $next($request);
    }
}
```

Add this middleware to "$routeMiddleware" group of "Kernel.php" file in app/Http.
```php
'acl' => \App\Http\Middleware\AccessControlList::class,
```


### Usage

Your configuration is almost complete. Now run the bellow php artisan command to create seed file for permission. But this will work only with resource controller. If you need any other method except default method then you have to modify the seed file yourself. 

``` bash
php artisan make:permission:seeder SeedFileName ResourceControllerRouteName ModuleName
```

Example :
For Web based resource controller 
``` bash
php artisan make:permission:seeder PermissionDepartmentSeeder department Department 
```

For api based resource controller 
``` bash
php artisan make:permission:seeder PermissionDepartmentSeeder department Department --api
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

We can modify this file as per as our requirements. The create & edit permission are omitted when you need API. 

### Route
Now we have to set this middleware in our route.
```php
Route::group(['middleware' => ['acl']], function () {
    Route::resource('/department', 'DepartmentController');
});
```

### N.B.
Please remember only department module is filtered with here. But you can filter any module with this ACL. 

