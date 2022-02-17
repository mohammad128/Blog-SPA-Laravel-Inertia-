<?php

namespace App\Http\Controllers;

use App\FlashMessage\Facade\FlashMessage;
use App\Models\Post;
use App\Notifications\CommentLikeNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{

    public function index() {
        Session()->flash('success', 'Test Message');
        $posts = Post::query()
            ->published()
            ->withCount(['comments'=>fn($query) => $query->approved()])
            ->when(\Illuminate\Support\Facades\Request::input('search'), function ($query, $search){
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate()
            ->withQueryString()
            ->through(fn($post)=> [
                'id' =>$post->id,
                'title' =>$post->title,
                'feature_image' => $post->feature_image,
                'url' => $post->url,
                'comments_count' => $post->comments_count,
                'has_password' => $post->has_password,
                'rate' =>$post->rate
            ]);
        return Inertia::render('Post/index', [
            'posts'=> $posts,
            '_filters' => [
                'search' => \Illuminate\Support\Facades\Request::input('search')
            ],
        ]);
    }

    public function postPreview($id) {
        $post = Post::find($id);
        if($post->has_password) return "Password Required";
        return [
            'id' => $post->id,
            'content' => $post->content,
            'feature_image' => $post->feature_image,
            'title' => $post->title,
            'user'=> [
                'name' => $post->user->name,
                'profile_photo_url' => $post->user->profile_photo_url,
                'id' => $post->user->id,
            ]
        ];
    }

    public function setPostRate(Post $post, Request $request) {
        $request->validate([
            'rate'=>['required', 'numeric', 'between:0,10']
        ]);
        $post->setUserRate($request->get('rate'));
        return redirect()->back();
    }
    public function like( Post $post, Request $request) {
        $request->validate([
            'type' => ['required', 'in:like,dislike'],
            'active' => ['required', 'boolean']
        ]);
        switch ($request->get('type')) {
            case 'like':
                if ($request->get('active'))
                    $post->likeThis();
                else
                    $post->removeLikeThis();
                break;
            case 'dislike':
                if ($request->get('active'))
                    $post->dislikeThis();
                else
                    $post->removeDislikeThis();
                break;
        }
        return redirect()->back();
    }

    public function show(Post $post)
    {
        $comments = $post->comments()
            ->approved()
            ->rootComments()
            ->select(['id','created_at','content', 'user_id'])
            ->with(['user'=>function($q){
                return $q->select(['id','created_at', 'profile_photo_path', 'first_name', 'last_name'])->with(['roles'=> function($query) {
                    return $query->select([ 'name']);
                }]);
            }])
            ->paginate();
        //paginate( $perPage = null, $columns = ['*'], $pageName = 'page', $page = null )

        return Inertia::render('Post/Show/Index', [
            "post" => $post,
            "comments" => $comments,
        ]);
    }

    public function getPostChildComment( Post $post, Request $request) {
        $comment_id = $request->get('comment_id');
        $page = $request->get('page');

        return $post
            ->comments()
            ->approved()
            ->childComments($comment_id)
            ->select(['id','created_at','content', 'user_id'])
            ->with(['user'=>function($q){
                return $q->select(['id','created_at', 'profile_photo_path','first_name', 'last_name'])->with(['roles'=> function($query) {
                    return $query->select([ 'name']);
                }]);
            }])
            ->paginate(5, ['*'], 'childCommentPage', $page );
    }

    public function createComment(Post $post, Request $request) {
        $request->validate([
            'content' => ['required'],
            'parent_id' => ['required'],
        ]);
        $content = $request->get('content');
        $parent_id = $request->get('parent_id');
        $reply_comment = 0;
        if( $parent_id!=0 ) {
            $comment = $post->comments()->with(['user'])->firstWhere('id', $parent_id);
            if ($comment->parent_id != 0) {
                $reply_comment = $parent_id;
                $parent_id = $comment->parent_id;
//                $content = '<span class="user_name cursor-pointer text-xs font-bold text-sky-700 px-2" data-user-url="'.route('User.index',['user'=>$comment->user->username]).'" data-for-comment="'.$request->get('parent_id').'">@'.$comment->user->name.'</span>'.$content;
            }
        }

        $post->comments()->create(
            [
                'content' => $content,
                'parent_id' => $parent_id,
                'user_id' => auth()->user()->id,
                'reply_comment' => $reply_comment
            ]
        );

        FlashMessage::success('Thanks for your comment', "Your comment has been sent. Wait for your opinion to be confirmed.");

        return redirect()->back();
    }

    public function likeComment( Post $post, Request $request) {

        $request->validate([
            'type' => ['required', 'in:like,dislike'],
            'active' => ['required', 'boolean'],
            'comment_id' => ['required', 'integer']
        ]);
        $comment = $post->comments()->with('user')->firstWhere('id', $request->get('comment_id'));

        if($comment->user->id != auth()->user()->id) {
            $comment->user->notify(new CommentLikeNotification(
                auth()->user(),
                $comment,
                $request->get('type'),
                $request->get('active'),
                $post->url
            ));
        }

        switch ($request->get('type')) {
            case 'like':
                if ($request->get('active'))
                    $comment->likeThis();
                else
                    $comment->removeLikeThis();
                break;
            case 'dislike':
                if ($request->get('active'))
                    $comment->dislikeThis();
                else
                    $comment->removeDislikeThis();
                break;
        }
        return redirect()->back();
    }
}
