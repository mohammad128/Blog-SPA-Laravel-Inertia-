<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $fillable = ['name'];
    public function items() {
        return $this->hasMany(MenuItem::class);
    }
}
