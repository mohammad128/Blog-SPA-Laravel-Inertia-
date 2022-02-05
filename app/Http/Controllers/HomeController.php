<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index() {
        return Inertia::render('Welcome',
            [
                'home_slider'=>Meta::query()->getHomeSlider(),
                'popular_posts' => Post::orderByRate()->published()->withCount(['comments'=>fn($q)=>$q->approved(), 'rate'])->limit(10)->get()->toArray(),
                'newst_posts' => Post::query()->published()->withCount(['comments'=>fn($q) => $q->approved(), 'rate'])->orderBy('created_at','desc')->limit(10)->get()->toArray()
            ]
        );
    }
}
