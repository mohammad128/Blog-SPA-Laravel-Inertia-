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
                'user:id,username'
            ])
            ->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts() )
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
            ->withCount(['comments as approvedComments'=> function ($q) {
                $q->approved();
            }])
            ->withCount(['comments as pendingComments'=> function ($q) {
                $q->pending();
            }])
            ->withCount(['comments as sapmComments'=> function ($q) {
                $q->spam();
            }])
            ->withCount(['comments as trashComments'=> function ($q) {
                $q->onlyTrashed();
            }])
            ->orderBy('id', 'desc')
            ->paginate($pre_page)->withQueryString();

        return Inertia::render('Dashboard/Post/index', [
            'categories' => app('rinvex.categories.category')->get()->map(function ($item){
                $item['post_count'] = app('rinvex.categories.category')->find($item['id'])->entries(\App\Models\Post::class)->count();
                return $item;
            })->toTree(),
            'post_count'=>[
                'all'=> Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts())->count(),
                'trash'=>Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts())->onlyTrashed()->count(),
                'draft'=>Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts())->draft()->count(),
                'published'=>Post::query()->when(!auth()->user()->can('read_all_post'), fn($q) => $q->userPosts())->published()->count(),
            ],
            'posts'=>$posts,
            'filters'=> [
                'selectedCat' => RequestFacade::input('selectedCat', ''),
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
//        $this->authorize('create');
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
//        $this->authorize('create');
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
        $this->authorize('update', $post);

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
        $this->authorize('update', $post);

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
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->back();
    }

    public function forceDelete( $id ) {
        $post = Post::withTrashed()->find($id);
        $this->authorize('forceDelete', $post);
        $post->forceDelete();
        return redirect()->back();
    }
    public function multiForceDelete(Request $request) {
//        $posts = Post::withTrashed()->whereIn( 'id', $request->get('ids', []) )->forceDelete();
        $posts = Post::withTrashed()->whereIn( 'id', $request->get('ids', []) )->get();
        foreach ($posts as $post) {
            $this->authorize('forceDelete', $post);
            $post->forceDelete();
        }
        return redirect()->back();
    }

    public function restore($id) {
        $post = Post::onlyTrashed()->find($id);
        $this->authorize('restore', $post);
        $post->restore();
        return redirect()->back();
    }

    public function multiRestore(Request $request) {
        $posts = Post::onlyTrashed()->whereIn('id', $request->get('ids'))->get();
        foreach ($posts as $post) {
            $this->authorize('restore', $post);
            $post->restore();
        }
        return redirect()->back();
    }

    public function destroy( Request $request ) {
        foreach ($request->get('ids') as $id) {
            $post = Post::query()->findOrFail($id);
            $this->authorize('delete', $post);
            $post->delete();
        }
//        Post::destroy($request->get('ids'));
        return redirect()->back();
    }

    public function publishedPost() {
        $posts = Post::query()
            ->select('title', 'id', 'updated_at', 'created_at', 'slug')
            ->when(RequestFacade::input('search'), function ($query) {
                return $query->where('title', 'LIKE', '%'.RequestFacade::input('search').'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(RequestFacade::input('pre_page')? RequestFacade::input('pre_page'): 15, ['*'], 'page', RequestFacade::input('page') )
            ->withQueryString();
        return $posts;
    }
}
