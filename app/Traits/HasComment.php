<?php
namespace App\Traits;


use App\Models\Comment;

trait HasComment {

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function addComment($message) {
        if(!auth()->check())
            return null;
        return $this->comments()->save(new \App\Models\Comment([
            'content'=> $message,
            'user_id'=> auth()->user()->id
        ]));
    }
}
