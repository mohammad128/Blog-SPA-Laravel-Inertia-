<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Baum
{
    use HasFactory;
    protected $fillable = [ 'title'. 'slug'. 'parent_id'. 'menu_id' ];


}
