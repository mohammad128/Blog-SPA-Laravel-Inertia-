<?php

namespace App\Http\Controllers\Dashboard;

use App\FlashMessage\Facade\FlashMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function create(Request $request) {
        $request->validate([
            'name' => ['required'],
            'parent_id'=>['numeric']
        ]);

        $attributes = [
            'name' => $request->get('name'),
            'parent_id' => $request->get('parent_id'),
            'slug' => Str::slug($request->get('slug'), "-"),
            'description' => $request->get('desc'),
        ];

        app('rinvex.categories.category')->create($attributes);

        FlashMessage::success('Created', "Category $request->get('name') was created successfully.");

        $redirect = $request->get('redirect') ?  $request->get('redirect') : route('dashboard.post.categories');
        return redirect($redirect);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => ['required'],
            'id'=>['numeric'],
            'parent_id'=>['numeric', 'different:id']
        ]);
        $category = app('rinvex.categories.category')->find($id);
        $slug = $request->get('slug') ? $request->get('slug') : $request->get('name');
        $attributes = [
            'name' => $request->get('name'),
            'parent_id' => $request->get('parent_id'),
            'slug' => Str::slug($slug, "-"),
            'description' => $request->get('description'),
        ];
        $category->update( $attributes );

        FlashMessage::success('Update', "Category $request->get('name') was updated successfully.");

        $redirect = $request->get('redirect') ?  $request->get('redirect') : route('dashboard.post.categories');
        return redirect($redirect);
    }

    public function delete( Request $request, $id ) {
        $category = app('rinvex.categories.category')->where('id', '=', $id);
        if( $category )
            $category->delete();

        FlashMessage::warning('Delete', "Category $request->get('name') was deleted.");

        $redirect = $request->get('redirect') ?  $request->get('redirect') : route('dashboard.post.categories');
        return redirect($redirect);
    }


    public function multiDelete( Request $request ) {
        foreach($request->get('selectedCats') as $catItem){
            $catId = $catItem['id'];
            $category = app('rinvex.categories.category')->where('id', '=', $catId);
            if( $category )
                $category->delete();
        }

        $count = count($request->get('selectedCats'));
        FlashMessage::error('Delete', "<b>$count</b> Category was deleted.");

        $redirect = $request->get('redirect') ?  $request->get('redirect') : route('dashboard.post.categories');
        return redirect($redirect);
    }


}
