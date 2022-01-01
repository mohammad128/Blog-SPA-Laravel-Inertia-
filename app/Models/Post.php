<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rinvex\Categories\Traits\Categorizable;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, SoftDeletes, Categorizable, HasTags;

    protected $fillable = ['title','content','feature_image', 'user_id', 'draft', 'disable_comment', 'password', 'slug'];

    protected $appends = ['updated_at_for_human', 'created_at_for_human'];

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

    /*
     * Local Scopes
     * */
    public function scopeDraft( $query ) {
        return $query->where('draft', true);
    }

    public function scopePublished( $query ) {
        return $query->where('draft', false);
    }

}
