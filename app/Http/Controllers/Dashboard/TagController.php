<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class TagController extends Controller
{

    public function index() {
        $tags = Tag::paginate()
            ->withQueryString();
        return Inertia::render('Dashboard/Post/Tags', [
            'tags'=> $tags
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>['required']
        ]);
        Tag::findOrCreate($request->get('name'));
        return redirect()->back();
    }

    public function update($id, Request $request) {
        $request->validate([
            'name'=>['required']
        ]);
        $tag = Tag::find($id);
        if($tag)
            $tag->update([
                'name'=>$request->get('name')
            ]);
        return redirect()->back();
    }

    public function delete($id) {
        $tag = Tag::find($id);
        if($tag)$tag->delete();
        return redirect()->back();
    }

    public function multiDelete(Request $request) {
        $request->validate([
            'ids'=>['required']
        ]);
        Tag::destroy( $request->get('ids') );
        return redirect()->back();
    }

}
