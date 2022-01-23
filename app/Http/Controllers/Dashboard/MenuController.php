<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\Request as RequestFacades;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index() {
        $menus = Menu::query()->select(['id', 'name', 'created_at'])->orderBy('id', 'asc')->get();

        return Inertia::render('Dashboard/Appearance/Menu/Index', [
            'menus'=> $menus,
        ]);
    }

    public function edit(Menu $menu) {
        $menus = Menu::query()->select(['id', 'name', 'created_at'])->orderBy('id', 'asc')->get();

//        $menu = $_menu->toArray();
        $menuItems = $menu->items()->select('id','text', 'href', 'menu_id', 'parent_id')->get()->toTree()->toArray();
        $menu = $menu->toArray();
        $menu['items']=$menuItems;

        return Inertia::render('Dashboard/Appearance/Menu/edit', [
            'menus'=> $menus,
            'menu'=> $menu
        ]);
    }

    public function delete(Menu $menu) {
        $menu->delete();
        return redirect()->back();
    }

}
