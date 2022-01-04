<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [ 'title', 'content', 'feature_image', 'draft', 'disable_comment', 'password', 'slug', 'user_id' ];


    public function user() {
        return $this->belongsTo(User::class);
    }



    public function scopeDraft($query) {
        $query->where('draft', true);
    }
    public function scopePublished($query) {
        $query->where('draft', false);
    }
}
