<?php

namespace Pingu\Menu\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

class MenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $perm = Permission::findOrCreate(['name' => 'manage menus', 'section' => 'Menu']);
        Permission::findOrCreate(['name' => 'add menus', 'section' => 'Menu']);
        Permission::findOrCreate(['name' => 'edit menus', 'section' => 'Menu']);
        Permission::findOrCreate(['name' => 'delete menus', 'section' => 'Menu']);
        Permission::findOrCreate(['name' => 'delete menu items', 'section' => 'Menu']);

        $menu = Menu::findByName('admin-menu');
        MenuItem::firstOrCreate(['name' => 'Menus','url' => 'menu.admin.menus'], [
            'weight' => 2,
            'active' => 1,
            'url' => 'menu.admin.menus',
            'permission_id' => $perm->id
        ], $menu);
    }
}
