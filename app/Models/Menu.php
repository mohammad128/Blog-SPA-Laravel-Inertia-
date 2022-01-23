<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $appends = ['diff_for_human'];

    public function items() {
        return $this->hasMany(MenuItem::class);
    }

    public function getDiffForHumanAttribute() {
        return $this->created_at->diffForHumans();
    }

}
