<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PageController extends Controller
{

    public function index() {
        $pre_page = RequestFacade::input('postPrePage') ? RequestFacade::input('postPrePage') : 15;
        $posts = Page::query()->with([
                'user:id,username'
            ])
            ->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )
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
            ->when(RequestFacade::input('sortKey') && RequestFacade::input('sortType'), function ($query) {
//                author
//                comment_count
//                created_at

                $sortKey = RequestFacade::input('sortKey');
                $sortType = RequestFacade::input('sortType');
                if( !in_array($sortType, ['desc', 'asc']) OR !in_array($sortKey, ['title', 'author', 'comment_count', 'created_at']) )
                    return $query;
                switch ($sortKey) {
                    case 'author':
                        return $query->join('users', 'pages.user_id', '=', 'users.id')
                            ->select('pages.*', 'users.username')
                            ->orderBy('username', $sortType);
                        break;
                    case 'title':
                        return $query->orderBy( 'title',  $sortType);
                        break;
                    case 'created_at':
                        return $query->orderBy( 'created_at',  $sortType);
                        break;
                    case 'comment_count':
                        return $query->orderBy( 'approvedComments',  $sortType);
                        break;
                }
                return $query;
            })
            ->when(RequestFacade::input('search'), function ($query) {
                return $query->where('title', 'LIKE', '%'.RequestFacade::input('search').'%');
            })
            ->when(RequestFacade::input('fromDate'),function ($query){
                $query->whereDate('created_at', '>=', RequestFacade::input('fromDate'));
            })
            ->when(RequestFacade::input('toDate'),function ($query){
                $query->whereDate('created_at', '<=', RequestFacade::input('toDate'));
            })
//            ->select(['id', 'title', 'user_id','updated_at', 'created_at', 'slug', 'draft'])
            ->orderBy('id', 'desc')
            ->paginate($pre_page)
            ->withQueryString();

        return Inertia::render('Dashboard/Page/index', [
            'posts' => $posts,
            'filters'=> [
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'search' => RequestFacade::input('search', ''),
                'post_type' => RequestFacade::input('post_type', ''),
                'postPrePage' => RequestFacade::input('postPrePage',15),
                'page' => RequestFacade::input('page', 1),
                'sortKey' => RequestFacade::input('sortKey', ''),
                'sortType' => RequestFacade::input('sortType', ''),
            ],
            'post_count'=>[
                'all'=> Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->count(),
                'trash'=>Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->onlyTrashed()->count(),
                'draft'=>Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->draft()->count(),
                'published'=>Page::query()->when(!auth()->user()->can('read_all_page'), fn($q) => $q->userPages() )->published()->count(),
            ],
        ]);
    }

    public function create() {
        $this->authorize('create', Page::class);
        return Inertia::render('Dashboard/Page/Create');
    }
    public function store( Request $request) {
        $this->authorize('create', Page::class);
        $request->validate([
            'title' => ['required'],
            'slug' => ['required', 'unique:posts'],
            'content' => ['required'],
            'feature_image' => ['required'],
//            'disible_comment' => ['required'],
//            'password' => ['required'],
//            'draft' => ['required'],
        ]);
        $password = $request->get('password')=='' ? null : Hash::make($request->get('password'));
        Page::create(  ['user_id' => auth()->user()->id, 'password'=> $password ] + $request->all() );
        return redirect()->route('dashboard.page.index');
    }

    public function edit( Page  $page) {
        $this->authorize('update', $page);
        return Inertia::render('Dashboard/Page/Edit', [
            'post' => $page
        ]);
    }
    public function update(Page $page, Request $request) {
        $this->authorize('update', $page);
        $request->validate([
            'title' => ['required'],
            'slug' => ['required',
                Rule::unique('posts')->ignore($page)
            ],
            'content' => ['required'],
            'feature_image' => ['required']
        ]);
        $password = $request->get('password')=='' ? null : Hash::make($request->get('password'));
        $page->update(  ['user_id' => auth()->user()->id, 'password'=> $password ] + $request->all() );
        return redirect()->route('dashboard.page.index');
    }


    public function delete( Page $page ) {
        $this->authorize('delete', $page);
        $page->delete();
        return redirect()->back();
    }

    public function multiDelete(Request $request) {
//        Page::destroy($request->get('ids'));
        $pages = Page::whereIn( 'id', $request->get('ids', []) )->get();
        foreach ($pages as $page) {
            $this->authorize('forceDelete', $page);
            $page->delete();
        }
        return redirect()->back();
    }

    public function multiForceDelete(Request $request) {
//        Page::withTrashed()->whereIn( 'id', $request->get('ids', []) )->forceDelete();
        $pages = Page::withTrashed()->whereIn( 'id', $request->get('ids', []) )->get();
        foreach ($pages as $page) {
            $this->authorize('forceDelete', $page);
            $page->forceDelete();
        }
        return redirect()->back();
    }
    public function forceDelete($id) {
//        Page::withTrashed()->find($id)->forceDelete();
        $page = Page::withTrashed()->find($id);
        $this->authorize('forceDelete', $page);
        $page->forceDelete();
        return redirect()->back();
    }

    public function restore($id) {
        $oage = Page::onlyTrashed()->find($id);
        $this->authorize('restore', $oage);
        $oage->restore();
        return redirect()->back();
    }
    public function multiRestore(Request $request) {
        $pages = Page::onlyTrashed()->whereIn('id', $request->get('ids'))->get();
        foreach ($pages as $page) {
            $this->authorize('restore', $page);
            $page->restore();
        }
        return redirect()->back();
    }




    public function publishedPage() {
        $posts = Page::query()
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
