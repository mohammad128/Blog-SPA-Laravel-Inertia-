<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rinvex\Categories\Traits\Categorizable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Categorizable;

    protected $fillable = ['title','content','feature_image', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
