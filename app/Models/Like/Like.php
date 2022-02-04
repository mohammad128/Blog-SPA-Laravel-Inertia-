<?php

namespace App\Models\Like;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [ 'like_count', 'dislike_count' ];

    public function likeable() {
        return $this->morphTo();
    }

}
