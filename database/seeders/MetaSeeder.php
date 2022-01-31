<?php

namespace Database\Seeders;

use App\Models\Meta;
use Illuminate\Database\Seeder;

class MetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meta::query()->updateSiteConfig(
            [
                'meta_value' => [
                    'site_title' => 'Laravel inertia',
                    'tag_line' => 'laravel inertia blog',
                    'new_user_default_role' => [],
                    'menus' => [
                        'header_menu' => 0,
                        'mobile_menu' => 0,
                        'footer_menu' => 0,
                    ]
                ]
            ]
        );
        Meta::query()->updateMeta(
            'home_slider',
            [
                'https://placeimg.com/900/700/any?1',
                'https://placeimg.com/900/700/any?2',
                'https://placeimg.com/900/700/any?3',
                'https://placeimg.com/900/700/any?4',
                'https://placeimg.com/900/700/any?5',
                'https://placeimg.com/900/700/any?6',
            ]
        );

//        Meta::query()->siteConfig()->updateOrCreate(
//            [ 'meta_key' => 'home_slider' ],
//            [
//                'meta_value' => json_encode( [ ] )
//            ]
//        );

    }
}
