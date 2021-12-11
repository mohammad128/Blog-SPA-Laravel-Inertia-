<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use mysql_xdevapi\Session;

class PostController extends Controller
{

    public function index() {
        sleep(2);

        Session()->flash('success', 'Test Message');
        $posts = Post::query()
            ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search){
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate()
            ->withQueryString()
            ->through(fn($post)=> [
                'id' =>$post->id,
                'title' =>$post->title,
                'feature_image' => $post->feature_image,
            ]);
        return Inertia::render('Post/index', [
            'posts'=> $posts,
            '_filters' => [
                'search' => \Illuminate\Support\Facades\Request::input('search')
            ],
        ]);
    }

    public function postPreview($id) {
        sleep(1);
        $post = Post::find($id);
        return [
            'id' => $post->id,
            'content' => $post->content,
            'feature_image' => $post->feature_image,
            'title' => $post->title,
            'user'=> [
                'name' => $post->user->name,
                'profile_photo_url' => $post->user->profile_photo_url,
                'id' => $post->user->id,
            ]
        ];
    }
}
