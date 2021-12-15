<?php

namespace App\Providers;

use App\FlashMessage\FlashMessage;
use Illuminate\Support\ServiceProvider;

class FlashMessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FlashMessage', function (){
            return new FlashMessage();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
