<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index() {
        $users = User::with('roles')->withCount(['posts','comments', 'pages'])->paginate();
        $roles = Role::all('id', 'name');
        return Inertia::render('Dashboard/User/Index', [
            'users'=> $users,
            '_roles'=> $roles
        ]);
    }

    public function create() {
        dd("Create User");
    }
}
