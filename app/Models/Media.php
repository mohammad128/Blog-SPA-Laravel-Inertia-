<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'path',
        'user_id',
        'type',
    ];

    protected $appends = [
        'media_url','diff_for_human'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getMediaUrlAttribute() {
        return  asset('storage/'.$this->path);
    }

    public function getDiffForHumanAttribute() {
        return $this->created_at->diffForHumans();
    }

}
