<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
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
        $menu['items'] = $menu->items()->defaultOrder()->get()->toTree()->toArray();//$menu->items()->select('id','text', 'href', 'menu_id', 'parent_id')->get()->toTree()->toArray();
        $menu['flatItems'] = $menu->items()->defaultOrder()->get()->toFlatTree()->map(function ($item){
            $tmp = [];
            $tmp[$item['text']] = [
                'id'=> $item['id'],
                'text'=> $item['text'],
                'href'=> $item['href'],
            ];
            return $tmp;
        });
        $menu = $menu->toArray();

        return Inertia::render('Dashboard/Appearance/Menu/edit', [
            'menus'=> $menus,
            'menu'=> $menu
        ]);
    }

    public function delete(Menu $menu) {
        $menu->delete();
        return redirect()->back();
    }



    public function deleteMenuItem( $id) {
        MenuItem::query()->where('id', '=', $id)->delete();
        return redirect()->back();
    }
    public function storeMenuItem( Menu $menu, Request $request) {
        $request->validate([
            'text'=> ['required'],
            'href'=> ['required'],
        ]);
        $node = new \App\Models\MenuItem( $request->only('text','href') );
        $node->makeRoot(); // Saved as root
        $menu->items()->save( $node );
        $menu->items()->fixTree();
        return redirect()->back();
    }
    public function moveNode($id, $parent_id) {
        dd($id, $parent_id);
    }

    public function rebuildMenuItems(Menu $menu, Request $request) {
        $menu->items()->rebuildTree($request->get('items'), true);
//        MenuItem::fixTree();
        $menu->items()->fixTree();
        return redirect()->back();
    }
}
