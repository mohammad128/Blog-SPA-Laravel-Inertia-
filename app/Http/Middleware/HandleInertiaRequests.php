<?php

namespace App\Http\Middleware;

use App\FlashMessage\Facade\FlashMessage;
use App\Models\Menu;
use App\Models\Meta;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $siteConfig = Meta::getSiteConfig();

        return array_merge(parent::share($request), [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'can' => function() {
                if( ! auth()->check() )
                    return [];
                return auth()->user()->getAllPermissions()->mapWithKeys(function ($item, $key) {
                    return [ $item['name'] => $item['id'] ];
                });
            },
            'roles' => function() {
                if (!auth()->check())
                    return [];
                return auth()->user()->roles()->get()->mapWithkeys(function ($item, $key) {
                    return [ $item['name'] => $item['id'] ];
                });
            },
            'flash_message'=> function() {
                $msg = FlashMessage::getAllMessages();
                Session::forget('messages');
                return $msg;
            },
            'site_menus' => [
                'header_menu' => !$siteConfig['menu']['header_menu'] ? [] : Menu::query()->firstWhere('id',$siteConfig['menu']['header_menu'])->items()->defaultOrder()->get()->toTree()->toArray(),
                'mobile_menu' => !$siteConfig['menu']['mobile_menu'] ? [] : Menu::query()->firstWhere('id',$siteConfig['menu']['mobile_menu'])->items()->defaultOrder()->get()->toTree()->toArray(),
                'footer_menu' => !$siteConfig['menu']['footer_menu'] ? [] : Menu::query()->firstWhere('id',$siteConfig['menu']['footer_menu'])->items()->defaultOrder()->get()->toTree()->toArray()
            ],
            'site_config' => [
                'site_title' => ''
            ]
        ]);
    }
}
