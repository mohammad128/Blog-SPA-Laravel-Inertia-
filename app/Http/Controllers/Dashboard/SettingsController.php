<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard/Settings/Index', [
            'menus'=> Menu::all('name','id', 'created_at'),
            '_roles'=> Role::all()
        ]);
    }
}
