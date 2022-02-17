<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request as RequestFacade;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $pre_page = RequestFacade::input('prePage') ? RequestFacade::input('prePage') : 15;

        $comments = Comment::query()->with(['user:id,email,username,profile_photo_path', 'commentable:id,created_at,updated_at,title,slug'])
            ->when(RequestFacade::input('comment_type'), function ($query) {
                switch ( RequestFacade::input('comment_type') ) {
                    case 'all':
                        return $query;
                        break;
                    case 'pending':
                        return $query->pending();
                        break;
                    case 'approved':
                        return $query->approved();
                        break;
                    case 'spam':
                        return $query->spam();
                        break;
                    case 'trash':
                        return $query->onlyTrashed();
                        break;
                }
                return $query;
            })
            ->when(RequestFacade::input('search'), function($query) {
                $query->where('content', 'LIKE', '%'.RequestFacade::input('search').'%');
            })
            ->when(RequestFacade::input('fromDate'),function ($query){
                $query->whereDate('created_at', '>=', RequestFacade::input('fromDate'));
            })
            ->when(RequestFacade::input('toDate'),function ($query){
                $query->whereDate('created_at', '<=', RequestFacade::input('toDate'));
            })
            ->when( RequestFacade::input('sortKey') && RequestFacade::input('sortType'), function($query) {
                $sortKey = RequestFacade::input('sortKey');
                $sortType = RequestFacade::input('sortType');
                if( !in_array($sortType, ['desc', 'asc']) OR !in_array($sortKey, ['username', 'comment', 'date', 'status']) )
                    return $query;
                switch ($sortKey) {
                    case 'username':
                        return $query->join('users', 'comments.user_id', '=', 'users.id')
                            ->select('comments.*', 'users.username')
                            ->orderBy('username', RequestFacade::input('sortType'));
                        break;
                    case 'comment':
                        return $query->orderBy( 'content',  RequestFacade::input('sortType'));
                        break;
                    case 'date':
                        return $query->orderBy( 'created_at',  RequestFacade::input('sortType'));
                        break;
                    case 'status':
                        return $query->orderBy( 'status',  RequestFacade::input('sortType'));
                        break;
                }
                return $query;
            }, function ($query) {
                return $query->orderBy('id', 'desc');
            })
            ->paginate($pre_page);
        $comments->through(function ($item) {
            if($item['parent_id'] != 0 ) {
                $c = Comment::select('id','created_at', 'content', 'user_id', 'parent_id')->with('user:id,email,username')->find($item['parent_id']);
                $result = $item;
                $result['parent'] = $c;
                return $result;
            }
            return $item;
        });
        return Inertia::render('Dashboard/Comments/index', [
            'comments' => $comments,
            'filters' => [
//                'comment_type' => RequestFacade::input('comment_type', 'all'),
                'page' => RequestFacade::input('page', 1),
                'comment_type' => RequestFacade::input('comment_type', ''),
                'search' => RequestFacade::input('search', ''),
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'prePage' => RequestFacade::input('prePage', 15),
                'sortKey' => RequestFacade::input('sortKey', ''),
                'sortType' => RequestFacade::input('sortType', ''),
            ],
            'comments_count' => [
                'all'=> Comment::count(),
                'pending'=> Comment::query()->pending()->count(),
                'approved'=> Comment::query()->approved()->count(),
                'spam'=> Comment::query()->spam()->count(),
                'trash'=> Comment::query()->onlyTrashed()->count(),
            ]
        ]);
    }

    public function trash(Comment $comment) {
        $comment->delete();
        return redirect()->back();
    }

    public function delete($id) {
        Comment::onlyTrashed()->find($id)->forceDelete();
        return redirect()->back();
    }
    public function restore($id) {
        Comment::onlyTrashed()->find($id)->restore();
        return redirect()->back();
    }

    public function deleteComments(Request $request) {
        $ids = $request->get('ids', []);
        $comments = Comment::onlyTrashed()->whereIn('id', $ids )->get();
        foreach ($comments as $comment)
            $comment->forceDelete();

        return redirect()->back();
    }
    public function restoreComments(Request $request) {
        $ids = $request->get('ids', []);
        $comments = Comment::onlyTrashed()->whereIn('id', $ids )->get();
        foreach ($comments as $comment)
            $comment->restore();
        return redirect()->back();
    }



    public function approveComments(Request $request) {
        $ids = $request->get('ids', []);
        $comments = Comment::query()->whereIn('id', $ids)->get();
        foreach ($comments as $comment)
            $comment->update(['status'=>'approve']);
        return redirect()->back();
    }
    public function trashComments(Request $request) {
        $ids = $request->get('ids', []);
        $comments = Comment::query()->whereIn('id', $ids)->get();
        foreach ($comments as $comment)
            $comment->delete();
        return redirect()->back();
    }
    public function spamComments(Request $request) {
        $ids = $request->get('ids', []);
        $comments = Comment::query()->whereIn('id', $ids)->get();
        foreach ($comments as $comment)
            $comment->update(['status'=>'spam']);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
