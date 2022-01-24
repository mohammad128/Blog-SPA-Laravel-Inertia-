<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class MenuItem extends Model
{
    use HasFactory, NodeTrait;
    protected $fillable = [ 'text', 'href', 'icon', 'external_link', 'parent_id', 'menu_id' ];


    public function menu() {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }

    protected function getRelationQuery()
    {
        return $this->query;
    }
}
