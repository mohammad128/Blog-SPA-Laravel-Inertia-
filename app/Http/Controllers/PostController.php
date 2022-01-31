<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{

    public function index() {
        Session()->flash('success', 'Test Message');
        $posts = Post::query()
            ->withCount('comments')
            ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search){
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate()
            ->withQueryString()
            ->through(fn($post)=> [
                'id' =>$post->id,
                'title' =>$post->title,
                'feature_image' => $post->feature_image,
                'url' => $post->url,
                'comments_count' => $post->comments_count
            ]);
        return Inertia::render('Post/index', [
            'posts'=> $posts,
            '_filters' => [
                'search' => \Illuminate\Support\Facades\Request::input('search')
            ],
        ]);
    }

    public function postPreview($id) {
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

    public function show(Post $post)
    {
        dd($post->toArray());
    }
}
