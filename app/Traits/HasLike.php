<?php
namespace App\Traits;


use App\Models\Like\Like;
use App\Models\Like\UserLike;

trait HasLike {

    public function userLike() {
        return $this->morphMany(UserLike::class, 'user_likeable');
    }

    public function like() {
        return $this->morphOne(Like::class, 'likeable');
    }

    public function likeThis() {
        if( !auth()->check() )
            return null;
        $user_like = $this->userLike()->updateOrCreate(
            [ 'user_id' => auth()->user()->id ],
            [ 'like'=>true, 'dislike'=>false ]
        );
        $this->onChangeLikes();
        return $user_like;
    }
    public function dislikeThis() {
        if( !auth()->check() )
            return null;
        $user_like = $this->userLike()->updateOrCreate(
            [ 'user_id' => auth()->user()->id ],
            [ 'like'=>false, 'dislike'=>true ]
        );
        $this->onChangeLikes();
        return $user_like;
    }

    public function removeLikeThis() {
        if( !auth()->check() )
            return null;
        $user_like = $this->userLike()->updateOrCreate(
            [ 'user_id' => auth()->user()->id ],
            [ 'like'=>false, 'dislike'=>false ]
        );
        $this->onChangeLikes();
        return $user_like;
    }
    public function removeDislikeThis() {
        if( !auth()->check() )
            return null;
        $user_like = $this->userLike()->updateOrCreate(
            [ 'user_id' => auth()->user()->id ],
            [ 'like'=>false, 'dislike'=>false ]
        );
        $this->onChangeLikes();
        return $user_like;
    }


    public function onChangeLikes() {
        $like_count = $this->userLike()->where('like', true)->count();
        $dislike_count = $this->userLike()->where('dislike', true)->count();

        $like = $this->like();
        if( ! $like->count() ) { // Create
            return $like->create( [
                'like_count' => $like_count,
                'dislike_count' => $dislike_count,
            ] );
        }else { // update
            return $like->update( [
                'like_count' => $like_count,
                'dislike_count' => $dislike_count,
            ] );
        }
    }


    public function getUserLikeStatusAttribute() {
        if(!auth()->check())
            return null;
        return $this->userLike()->where('user_id', auth()->user()->id )->select(['dislike', 'like'])->get()->first();
    }


    public function getLikeStatusAttribute() {
        return $this->like()->select('like_count','dislike_count')->get()->first();
    }

}
