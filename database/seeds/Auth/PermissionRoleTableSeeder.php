<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        Role::create(['name' => config('access.users.admin_role')]);
        $executive = Role::create(['name' => 'executive']);
        Role::create(['name' => config('access.users.default_role')]);


        // Create Permissions
        $permissions = [
            ['name'  => 'view backend']

        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign Permissions to other Roles
        // Note: Admin (User 1) Has all permissions via a gate in the AuthServiceProvider
        $executive->givePermissionTo('view backend');

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        \Illuminate\Support\Facades\Artisan::call('joydata:seed:permissions' , [] , $output);
        echo $output->fetch();

        $this->enableForeignKeys();
    }
}
