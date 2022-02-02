<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $fillable = ['meta_key', 'meta_value'];

    public function scopeUpdateMeta( $query, $meta_key, $meta_value ) {
        if(is_array( $meta_value )) $meta_value = json_encode($meta_value,true);
        return $query->updateOrCreate(
            ['meta_key' => $meta_key ],
            [ 'meta_value' => $meta_value ]
        );
    }
    public function scopeGetMeta($query, $meta_key) {
        return json_decode(  $query->where('meta_key', $meta_key)->first()->meta_value, true );
    }

    public function scopeSiteConfig($query) {
        return $query->where('meta_key', 'site_config');
    }
    public function scopeUpdateSiteConfig( $query, $meta_value ) {
        return $query->updateMeta( 'site_config', $meta_value );
    }
    public function scopeGetSiteConfig($query) {
        return json_decode( $query->siteConfig()->first()->meta_value, true );
//        return json_decode( $query->homeSlider()->get()->first()['home_slider'], true );
    }


    public function scopeHomeSlider($query) {
        return $query->where('meta_key', 'home_slider');
    }
    public function scopeGetHomeSlider($query) {
        return json_decode( $query->homeSlider()->first()->meta_value, true );
//        return json_decode( $query->homeSlider()->get()->first()['home_slider'], true );
    }


}
