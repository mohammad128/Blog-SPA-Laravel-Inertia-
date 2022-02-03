<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rate'];

    public function userRateable() {
        return $this->morphTo();
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}
