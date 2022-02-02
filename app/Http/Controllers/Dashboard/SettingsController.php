<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Meta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard/Settings/Index', [
            'menus'=> Menu::all('name','id', 'created_at'),
            '_roles'=> Role::all(),
            'site_config' => Meta::getSiteConfig(),

            'APP_NAME' => env('APP_NAME', ''),
            'APP_URL' => env('APP_URL', ''),
        ]);
    }

    public function update(Request $request) {
//        dd($request->only([
//            'new_user_default_role',
//            'site_title',
//            'tag_line',
//            'menu',
//        ]));
        $request->validate([
            'app_name' => ['required'],
            'app_url' => ['required'],
            'site_title' => ['required'],
            'tag_line' => ['required'],

//            'default_user_role' => ['required'],
        ]);

        $this->setEnv('APP_NAME', str_replace(' ', '', $request->get('app_name')));
        $this->setEnv('APP_URL', $request->get('app_url'));
//        $this->setEnv('SITE_TITLE', str_replace(' ', '_', $request->get('site_title')));
//        $this->setEnv('TAG_LINE', $request->get('tag_line'));
        $this->setEnv('NEW_USER_DEFUALT_ROLE', $request->get('default_user_role'));

        Meta::updateSiteConfig( $request->only([
            'new_user_default_role',
            'site_title',
            'tag_line',
            'menu',
        ]) );

        Meta::updateMeta('socials', $request->get('socials'));

        return redirect()->back();
    }


    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($key),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}
