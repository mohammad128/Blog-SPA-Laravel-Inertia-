<?php

namespace App\Models\Like;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;

    protected $fillable = [ 'like', 'dislike', 'user_id' ];

    public function user_likeable() {
        return $this->morphTo();
    }


}
