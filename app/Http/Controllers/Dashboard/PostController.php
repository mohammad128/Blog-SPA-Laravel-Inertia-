<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'categories' => app('rinvex.categories.category')->get()->toTree()
        ];
        return Inertia::render('Dashboard/Post/Categories', $data);
    }
}
