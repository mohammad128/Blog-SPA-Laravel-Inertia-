<?php

namespace App\Http\Controllers\Dashboard;

use App\FlashMessage\Facade\FlashMessage;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class PostController extends Controller
{
    public function allPosts() {
        return Inertia::render('Dashboard/Post/AllPosts');
    }
    public function create() {
        $data = [
            'categories' => app('rinvex.categories.category')->get()->toTree()
        ];
        return Inertia::render('Dashboard/Post/Create', $data);
    }
    public function categories() {
        $data = [
            'categories' => app('rinvex.categories.category')->get()->map(function ($item){
                $item['post_count'] = app('rinvex.categories.category')->find($item['id'])->entries(\App\Models\Post::class)->count();
                return $item;
            })->toTree()
        ];
        return Inertia::render('Dashboard/Post/Categories', $data);
    }

    public function tags() {
        return Inertia::render('Dashboard/Post/Tags');
    }
}
