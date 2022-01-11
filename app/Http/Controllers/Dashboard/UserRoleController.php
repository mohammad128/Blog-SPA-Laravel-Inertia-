<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard/User/UserRole',[
            'permissions' => get_json_permissions(),
            'roles' => Role::with('permissions:id,name')->get()
        ]);
    }

    public function create() {
        return Inertia::render('Dashboard/User/CreateRole',[
            'permissions' => get_json_permissions(),
        ]);
    }
    public function store(Request $request) {
        $request->validate([
            'name'=> ['required', 'string', 'unique:roles'],
            'permissions'=> ['required','array']
        ]);
        $permissions = Permission::query()->whereIn('name', $request->get('permissions'))->get();
        $role = Role::create(['name' => $request->get('name'), 'guard_name'=>'web']);
        $role->syncPermissions($permissions);
        return redirect()->route('dashboard.user.role');
    }

    public function delete(Role $role) {
        $role->delete();
        return redirect()->back();
    }
    public function multiDelete(Request $request) {
        Role::query()->whereIn('id', $request->get('ids'))->delete();
        return redirect()->back();
    }
    public function edit( Role $role, Request $request ) {
        return Inertia::render('Dashboard/User/EditRole', [
            'permissions' => get_json_permissions(),
            'role'=> [
                'name'=>$role->name,
                'permissions'=>$role->permissions()->select('id','name')->get()
            ]
        ]);
    }

    public function update( Role $role, Request $request ){
        $request->validate([
            'name'=> ['required', 'string', Rule::unique('roles')->ignore($role)],
            'permissions'=> ['required','array']
        ]);
        $permissions = Permission::query()->whereIn('name', $request->get('permissions'))->get();
        $role->update(['name' => $request->get('name'), 'guard_name'=>'web']);
        $role->syncPermissions($permissions);
        return redirect()->route('dashboard.user.role');
    }
}
