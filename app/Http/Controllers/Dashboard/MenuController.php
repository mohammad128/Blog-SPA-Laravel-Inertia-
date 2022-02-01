<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacades;
use Illuminate\Validation\Rule;
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

        $menu['items'] = $menu->items()->defaultOrder()->get()->toTree()->toArray();
        $menu = $menu->toArray();

        return Inertia::render('Dashboard/Appearance/Menu/edit', [
            'menus'=> $menus,
            'menu'=> $menu
        ]);
    }

    public function delete(Menu $menu) {
        $menu->delete();
        return redirect()->route('dashboard.appearance.menu');
    }

    public function store(Request $request) {
        $request->validate([
            'name'=> ['required', 'unique:menus']
        ]);
        Menu::create(
            $request->only('name')
        );
        return redirect()->back();
    }
    public function update(Menu $menu, Request $request) {
        $request->validate([
            'name'=> ['required', Rule::unique('menus')->ignore($menu)]
        ]);
        $menu->update(
            $request->only('name')
        );
        return redirect()->route('dashboard.appearance.menu');
    }



    public function deleteMenuItem( $id ) {
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
    public function storeMenuItems( Menu $menu, Request $request) {
        $request->validate([
            'items'=> ['array'],
        ]);
        foreach($request->get('items') as $item) {
            if($item['text'] and $item['href']) {
                $node = new \App\Models\MenuItem( $item );
                $node->makeRoot(); // Saved as root
                $menu->items()->save($node);
            }
        }
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

    public function updateMenuItem(MenuItem $menuItem, Request $request) {
        $request->validate([
            'text'=> ['required'],
            'href'=> ['required'],
        ]);
        $menuItem->update($request->only('text','href','icon'));
        return redirect()->back();
    }
}
