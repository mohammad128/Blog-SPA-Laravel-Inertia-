<?php

namespace App\Http\Controllers\Dashboard;

use App\FlashMessage\Facade\FlashMessage;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\Request as RequestFacade;

class PostController extends Controller
{
    public function allPosts() {
        $pre_page = RequestFacade::input('postPrePage') ? RequestFacade::input('postPrePage') : 15;
            $posts = Post::query()->with([
                    'categories:id,name',
                    'tags' => function($query) {
                        $query->select('id','name');
                    },
                    'user:id,name'
                ])
                ->when(RequestFacade::input('post_type'), function ($query) {
                    switch (RequestFacade::input('post_type')) {
                        case 'all':
                            return $query;
                        case 'trash':
                            return $query->onlyTrashed();
                        case 'draft':
                            return $query->draft();
                        case 'published':
                            return $query->published();
                    }
                    return $query;
                })
                ->when(RequestFacade::input('search'), function ($query) {
                    return $query->where('title', 'LIKE', '%'.RequestFacade::input('search').'%');
                })
                ->when(RequestFacade::input('fromDate'),function ($query){
                    $query->whereDate('updated_at', '>=', RequestFacade::input('fromDate'));
                })
                ->when(RequestFacade::input('toDate'),function ($query){
                    $query->whereDate('updated_at', '<=', RequestFacade::input('toDate'));
                })
                ->select(['id', 'title', 'user_id','updated_at', 'created_at', 'slug', 'draft'])
                ->orderBy('id', 'desc')
                ->paginate($pre_page)->withQueryString();

        return Inertia::render('Dashboard/Post/AllPosts', [
            'categories' => app('rinvex.categories.category')->get()->map(function ($item){
                $item['post_count'] = app('rinvex.categories.category')->find($item['id'])->entries(\App\Models\Post::class)->count();
                return $item;
            })->toTree(),
            'post_count'=>[
                'all'=> Post::all()->count(),
                'trash'=>Post::onlyTrashed()->count(),
                'draft'=>Post::draft()->count(),
                'published'=>Post::published()->count(),
            ],
            'posts'=>$posts,
            'filters'=> [
                'selectedCat' => '57-60',//RequestFacade::input('selectedCat', ''),
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'search' => RequestFacade::input('search', ''),
                'post_type' => RequestFacade::input('post_type', ''),
                'postPrePage' => RequestFacade::input('postPrePage',15),
                'page' => RequestFacade::input('page', 1),
            ]
        ]);
    }
    public function create() {
        $data = [
            'categories' => app('rinvex.categories.category')->get()->toTree(),
            'tags' => Tag::get(['name'])->map(function ($item) {
                return [ 'value'=>$item['name'] ];
            })
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


    public function store( Request $request) {
        $request->validate([
            'title' => ['required'],
            'slug' => ['required', 'unique:posts'],
//            'categuries' => ['required'],
            'content' => ['required'],
//            'tags' => ['required'],
            'feature_image' => ['required'],
//            'disible_comment' => ['required'],
//            'password' => ['required'],
//            'draft' => ['required'],
        ]);
        $password = $request->get('password')=='' ? null : Hash::make($request->get('password'));
        $post = Post::create(  ['user_id' => auth()->user()->id, 'password'=> $password ] + $request->all() );
        $post->syncTags($request->get('tags'));
        $post->attachCategories($request->get('categuries'));
        return redirect()->route('dashboard.post.allPosts');
    }


    public function edit( Post  $post) {
        $post->load(['categories:id,name','tags:id,name']);
        return Inertia::render('Dashboard/Post/Edit', [
            'post'=>$post,
            'categories' => app('rinvex.categories.category')->get()->toTree(),
            'tags' => Tag::get(['name'])->map(function ($item) {
                return [ 'value'=>$item['name'] ];
            })
        ]);
    }
    public function update( Post  $post, Request $request) {
//        dd($request->all());
        $request->validate([
            'title' => ['required'],
            'slug' => ['required',
//                Rule::unique('posts')->where(function ($query) use ($request,$post) {
//                    return $query->where('id','!=', $post->id);
//                }),
                Rule::unique('posts')->ignore($post)
            ],
//            'categuries' => ['required'],
            'content' => ['required'],
//            'tags' => ['required'],
            'feature_image' => ['required'],
//            'disible_comment' => ['required'],
//            'password' => ['required'],
//            'draft' => ['required'],
        ]);
        $password = $request->get('password')=='' ? null : Hash::make($request->get('password'));
        $post->update(  ['user_id' => auth()->user()->id, 'password'=> $password ] + $request->all() );
        $post->syncTags($request->get('tags'));
        $post->attachCategories($request->get('categuries'));
        return redirect()->route('dashboard.post.allPosts');
    }

    public function delete( Post $post ) {
        $post->delete();
        return redirect()->back();
    }

    public function destroy( Request $request ) {
        Post::destroy($request->get('ids'));
        return redirect()->back();
    }

}
