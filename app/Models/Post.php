<?php

namespace App\Models;

use App\Traits\HasComment;
use App\Traits\HasLike;
use App\Traits\HasRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rinvex\Categories\Traits\Categorizable;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, SoftDeletes, Categorizable, HasTags, HasRate, HasLike, HasComment;

    protected $fillable = ['title','content','feature_image', 'user_id', 'draft', 'disable_comment', 'password', 'slug' ];

    protected $appends = ['updated_at_for_human', 'created_at_for_human', 'url', 'has_password', 'rate','user_rate', 'user_like_status', 'like_status'];
    protected $hidden = ['password'];

    public function user() {
        return $this->belongsTo(User::class);
    }


    /*
     * Mutator And Accessor
     * */
    public function getUpdatedAtForHumanAttribute() {
        return $this->updated_at->diffForHumans();
    }
    public function getCreatedAtForHumanAttribute() {
        return $this->created_at->diffForHumans();
    }
    public function getUrlAttribute() {
        return route('Post.show', ['post'=>$this->slug]);
    }
    public function getHasPasswordAttribute() {
        return $this->password ? true : false;
    }

    /*
     * Local Scopes
     * */
    public function scopeDraft( $query ) {
        return $query->where('draft', true);
    }

    public function scopePublished( $query ) {
        return $query->where('draft', false);
    }

    public function scopeUserPosts($query) {
        if( auth()->check() ) {
            return $query->where('user_id', auth()->user()->id);
        }
        return $query;
    }

}
