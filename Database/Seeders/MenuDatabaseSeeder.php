<?php

namespace Pingu\Menu\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;

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

        $check = Permission::where(['name' => 'view menus'])->first();
        if(!$check){
            $perm = Permission::create(['name' => 'view menus', 'section' => 'Menu']);
            Role::find(4)->givePermissionTo([
                $perm,
                Permission::findOrCreate(['name' => 'add menus', 'section' => 'Menu']),
                Permission::findOrCreate(['name' => 'edit menus', 'section' => 'Menu']),
                Permission::findOrCreate(['name' => 'delete menus', 'section' => 'Menu']),
                Permission::findOrCreate(['name' => 'edit menu items', 'section' => 'Menu']),
                Permission::findOrCreate(['name' => 'create menu items', 'section' => 'Menu']),
                Permission::findOrCreate(['name' => 'delete menu items', 'section' => 'Menu']),
            ]);

            $menu = Menu::findByName('admin-menu');
            $structure = MenuItem::findByName('admin-menu.structure');
            MenuItem::firstOrCreate(['name' => 'Menus','url' => 'menu.admin.menus'], [
                'weight' => 2,
                'active' => 1,
                'url' => 'menu.admin.menus',
                'deletable' => 0,
                'permission_id' => $perm->id
            ], $menu, $structure);
        }
    }
}
