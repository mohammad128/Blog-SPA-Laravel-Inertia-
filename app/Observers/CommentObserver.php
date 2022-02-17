<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentNotification;
use App\Notifications\ReplyCommentNotification;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $comment = Comment::query()->firstWhere('id', $comment->id);
        $modelUrl = app($comment->commentable_type)->firstWhere('id', $comment->commentable_id)->url;
        $comment->user->notify(new CommentNotification($comment,$modelUrl));
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        $modelUrl = app($comment->commentable_type)->firstWhere('id', $comment->commentable_id)->url;
        $comment->user->notify(new CommentNotification($comment, $modelUrl));

        if($comment->status == 'approve') {
            if($comment->parent_id) {
                $parentComment = Comment::query()->firstWhere('id', $comment->parent_id);
                $parentComment['user'] = $parentComment->user;
                if( $comment->reply_comment ) {
                    $replyComment = Comment::query()->firstWhere('id', $comment->reply_comment);
                    $replyComment['user'] = $replyComment->user;
                    if($parentComment->user->id == $replyComment->user->id) {
                        if($replyComment->user->id != $comment->user->id) {
//                            dump('replyComment', $replyComment);
                            $replyComment->user->notify( new ReplyCommentNotification($comment, $replyComment, $modelUrl) );
                        }
                    }
                    else {
                        if($replyComment->user->id != $comment->user->id) {
//                            dump('replyComment', $replyComment);
                            $replyComment->user->notify( new ReplyCommentNotification($comment, $replyComment, $modelUrl) );
                        }
                        if($parentComment->user->id != $comment->user->id) {
//                            dump('$parentComment', $parentComment);
                            $parentComment->user->notify( new ReplyCommentNotification($comment, $parentComment, $modelUrl) );
                        }
                    }

//                    $replyComment->user->notify(new CommentNotification($comment, $replyComment, $comment->user));
                }
                else {
                    if($parentComment->user->id != $comment->user->id) {
//                        dump('$parentComment', $parentComment);
                        $parentComment->user->notify( new ReplyCommentNotification($comment, $parentComment, $modelUrl) );
                    }
                }
            }
        }
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $comment['status'] = 'deleted';
        $modelUrl = app($comment->commentable_type)->firstWhere('id', $comment->commentable_id)->url;
        $comment->user->notify(new CommentNotification($comment,$modelUrl));
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        $modelUrl = app($comment->commentable_type)->firstWhere('id', $comment->commentable_id)->url;
        $comment->user->notify(new CommentNotification($comment,$modelUrl));
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
