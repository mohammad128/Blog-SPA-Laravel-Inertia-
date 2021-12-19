<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = get_json_permissions();
        $roles = [
            'admin' => $permissions,
        ];
        foreach ($roles as $role_name=>$role_permissions) {
            $__role_permissions = [];
            foreach ($role_permissions as $permission_cat => $_p) {
                foreach ($_p['permissions'] as $__p) {
                    $name = $__p['name'];
                    $desc = $__p['desc'];
                    array_push($__role_permissions, Permission::create(['name'=>$name]) );
                }
            }
            $r = Role::create(['name'=>$role_name]);
            $r->syncPermissions($__role_permissions);
        }

//        $__p = [];
//        foreach ($permisions as $key=>$permission) {
//            foreach ($permission as  $p) {
//                $__permision = $key.'.'.$p;
//                array_push($__p, Permission::create(['name'=>$__permision]) );
//            }
//        }
//
//        foreach ($roles as $_role=>$_permissions) {
//            $r = Role::create(['name'=>$_role]);
//            $r->syncPermissions($__p);
//        }
    }

}
