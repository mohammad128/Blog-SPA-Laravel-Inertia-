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

        return Inertia::render('Dashboard/Page/index', [
            'posts' => $posts,
            'filters'=> [
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'search' => RequestFacade::input('search', ''),
                'post_type' => RequestFacade::input('post_type', ''),
                'postPrePage' => RequestFacade::input('postPrePage',15),
                'page' => RequestFacade::input('page', 1),
            ],
            'post_count'=>[
                'all'=> Page::all()->count(),
                'trash'=>Page::onlyTrashed()->count(),
                'draft'=>Page::draft()->count(),
                'published'=>Page::published()->count(),
            ],
        ]);
    }

    public function create() {
        return Inertia::render('Dashboard/Page/Create');
    }
    public function store( Request $request) {
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
        return Inertia::render('Dashboard/Page/Edit', [
            'post' => $page
        ]);
    }
    public function update(Page $page, Request $request) {
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
        $page->delete();
        return redirect()->back();
    }

    public function destroy(Request $request) {
        Page::destroy($request->get('ids'));
        return redirect()->back();
    }

    public function multiForceDelete(Request $request) {
        Page::withTrashed()->whereIn( 'id', $request->get('ids', []) )->forceDelete();
        return redirect()->back();
    }
    public function forceDelete($id) {
        Page::withTrashed()->find($id)->forceDelete();
        return redirect()->back();
    }

    public function restore($id) {
        Page::onlyTrashed()->find($id)->restore();
        return redirect()->back();
    }
    public function multiRestore(Request $request) {
        Page::onlyTrashed()->whereIn('id', $request->get('ids'))->restore();
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
