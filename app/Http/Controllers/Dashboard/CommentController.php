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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pre_page = RequestFacade::input('prePage') ? RequestFacade::input('prePage') : 15;

        $comments = Comment::query()->with(['user:id,email,name'])
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
            ->paginate($pre_page);
        $comments->through(function ($item) {
            if($item['parent_id'] != 0 ) {
                $c = Comment::select('id','created_at', 'content', 'user_id', 'parent_id')->with('user:id,email,name')->find($item['parent_id']);
                $result = $item;
                $result['parent'] = $c;
                return $result;
            }
            return $item;
        });
        return Inertia::render('Dashboard/Comments/index', [
            'comments' => $comments,
            'filters' => [
                'comment_type' => RequestFacade::input('comment_type', 'all'),
                'page' => RequestFacade::input('page', 1),
                'comment_type' => RequestFacade::input('comment_type', ''),
                'search' => RequestFacade::input('search', ''),
                'fromDate' => RequestFacade::input('fromDate', ''),
                'toDate' => RequestFacade::input('toDate', ''),
                'prePage' => RequestFacade::input('prePage', 15),
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
        Comment::onlyTrashed()->whereIn('id', $ids )->forceDelete();
        return redirect()->back();
    }
    public function restoreComments(Request $request) {
        $ids = $request->get('ids', []);
        Comment::onlyTrashed()->whereIn('id', $ids )->restore();
        return redirect()->back();
    }



    public function approveComments(Request $request) {
        $ids = $request->get('ids', []);
        Comment::query()->whereIn('id', $ids)->update(['status'=>'approve']);
        return redirect()->back();
    }
    public function trashComments(Request $request) {
        $ids = $request->get('ids', []);
        Comment::query()->whereIn('id', $ids)->delete();
        return redirect()->back();
    }
    public function spamComments(Request $request) {
        $ids = $request->get('ids', []);
        Comment::query()->whereIn('id', $ids)->update(['status'=>'spam']);
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
