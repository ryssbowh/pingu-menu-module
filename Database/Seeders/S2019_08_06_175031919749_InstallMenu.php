<?php

use Illuminate\Database\Eloquent\Model;
use Pingu\Core\Seeding\DisableForeignKeysTrait;
use Pingu\Core\Seeding\MigratableSeeder;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

class S2019_08_06_175031919749_InstallMenu extends MigratableSeeder
{
    use DisableForeignKeysTrait;

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Model::unguard();

        $perm = Permission::create(['name' => 'view menus', 'section' => 'Menu']);
        Role::find(4)->givePermissionTo(
            [
            $perm,
            Permission::findOrCreate(['name' => 'add menus', 'section' => 'Menu']),
            Permission::findOrCreate(['name' => 'edit menus', 'section' => 'Menu']),
            Permission::findOrCreate(['name' => 'delete menus', 'section' => 'Menu']),
            Permission::findOrCreate(['name' => 'edit menu items', 'section' => 'Menu']),
            Permission::findOrCreate(['name' => 'create menu items', 'section' => 'Menu']),
            Permission::findOrCreate(['name' => 'delete menu items', 'section' => 'Menu']),
            ]
        );

        $menu = Menu::findByMachineName('admin-menu');
        $structure = MenuItem::findByMachineName('admin-menu.structure');
        MenuItem::firstOrCreate(
            ['name' => 'Menus','url' => 'menu.admin.index'], [
            'weight' => 2,
            'active' => 1,
            'url' => 'menu.admin.index',
            'deletable' => 0,
            'permission_id' => $perm->id
            ], $menu, $structure
        );
    }

    /**
     * Reverts the database seeder.
     */
    public function down(): void
    {
        // Remove your data
    }
}
