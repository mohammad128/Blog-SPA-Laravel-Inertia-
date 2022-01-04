<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
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
        dd("Create Page");
    }
    public function store( Request $request) {
        dd($request->all());
    }

    public function edit() {
        dd("Create Page");
    }
    public function update(Page $page, Request $request) {
        dd($page,$request->all());
    }


    public function delete( Page $page ) {
        $page->delete();
        return redirect()->back();
    }


}
