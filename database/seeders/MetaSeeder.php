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
                'site_title' => 'Laravel inertia',
                'tag_line' => 'laravel inertia blog',
                'new_user_default_role' => [],
                'menu' => [
                    'header_menu' => 0,
                    'mobile_menu' => 0,
                    'footer_menu' => 0,
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


        $socials =  [
            [ 'value'=>'',      'name'=> 'facebook',        'color'=> 'facebook',        'icon'=>'bx bxl-facebook-square'],
            [ 'value'=>'',      'name'=> 'twitter',         'color'=> 'twitter',         'icon'=>'bx bxl-twitter'],
            [ 'value'=>'',      'name'=> 'youtube',         'color'=> 'youtube',         'icon'=>'bx bxl-youtube'],
            [ 'value'=>'',      'name'=> 'linkedin',        'color'=> 'linkedin',        'icon'=>'bx bxl-linkedin'],
            [ 'value'=>'',      'name'=> 'whatsapp',        'color'=> 'whatsapp',        'icon'=>'bx bxl-whatsapp'],
            [ 'value'=>'',      'name'=> 'twitch',          'color'=> 'twitch',          'icon'=>'bx bxl-twitch'],
            [ 'value'=>'',      'name'=> 'medium',          'color'=> 'medium',          'icon'=>'bx bxl-medium'],
            [ 'value'=>'',      'name'=> 'skype',           'color'=> 'skype',           'icon'=>'bx bxl-skype'],
            [ 'value'=>'',      'name'=> 'slack',           'color'=> 'slack',           'icon'=>'bx bxl-slack-old'],
            [ 'value'=>'',      'name'=> 'messenger',       'color'=> 'messenger',       'icon'=>'bx bxl-messenger'],
            [ 'value'=>'',      'name'=> 'tumblr',          'color'=> 'tumblr',          'icon'=>'bx bxl-tumblr'],
            [ 'value'=>'',      'name'=> 'dribbble',        'color'=> 'dribbble',        'icon'=>'bx bxl-dribbble'],
            [ 'value'=>'',      'name'=> 'google-plus',     'color'=> 'google-plus',     'icon'=>'bx bxl-google-plus'],
            [ 'value'=>'',      'name'=> 'vimeo',           'color'=> 'vimeo',           'icon'=>'bx bxl-vimeo'],
            [ 'value'=>'',      'name'=> 'pinterest',       'color'=> 'pinterest',       'icon'=>'bx bxl-pinterest'],
            [ 'value'=>'',      'name'=> 'spotify',         'color'=> 'spotify',         'icon'=>'bx bxl-spotify'],
            [ 'value'=>'',      'name'=> 'discord',         'color'=> 'discord',         'icon'=>'bx bxl-discord'],
            [ 'value'=>'',      'name'=> 'amazon',          'color'=> 'amazon',          'icon'=>'bx bxl-amazon'],
            [ 'value'=>'',      'name'=> 'reddit',          'color'=> 'reddit',          'icon'=>'bx bxl-reddit'],
        ];
        Meta::query()->updateMeta(
            'socials',
            $socials
        );

//        Meta::query()->siteConfig()->updateOrCreate(
//            [ 'meta_key' => 'home_slider' ],
//            [
//                'meta_value' => json_encode( [ ] )
//            ]
//        );

    }
}
