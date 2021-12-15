<?php
namespace App\FlashMessage\Facade;

use Illuminate\Support\Facades\Facade;

class FlashMessage extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'FlashMessage';
    }
}
