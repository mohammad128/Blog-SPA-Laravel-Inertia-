<?php
namespace App\Traits;

use App\Models\Rate;
use App\Models\UserRate;

trait HasRate {

    public function rate() {
        return $this->morphOne(Rate::class, 'rateable');
    }

    public function userRates() {
        return $this->morphMany(UserRate::class, 'user_rateable');
    }

    public function setUserRate($rate) {
        if(! auth()->check() )
            return false;
        $_r= $this->userRates()->updateOrCreate(
            ['user_id'=>auth()->user()->id],
            ['rate'=>$rate]
        );
        $this->onChangeRate();
        return $_r;
    }

//    public function rate() {
//        return $this->userRates()->avg('rate');
//    }

    public function getRateAttribute() {
        $rate = $this->rate();
        if(!$rate->count())
            return null;
        return $rate->first(['rate','rate_count'])->toArray();
    }

    public function getUserRateAttribute() {
        if(!auth()->check())
            return null;
        $rate = null;
        $r = $this->userRates()->firstWhere('user_id', auth()->user()->id);
        if($r) $rate=$r['rate'];
        return $rate;
    }

    public function onChangeRate() {
        $rate = $this->rate();

        $rate_count = $this->userRates()->count();
        if (!$rate_count)
            return null;
        $total_rate = $this->userRates()->avg('rate');
        if( ! $rate->count() ) { //Create
            $_rate = new Rate([
                'rate' => $total_rate,
                'rate_count' => $rate_count
            ]);
            $rate->save($_rate);
        }
        else { //update
            $rate->update([
                'rate' => $total_rate,
                'rate_count' => $rate_count
            ]);
        }
        return $rate->get();
//        $this->rate()->updateOrCreate(
//            ['' => ],
//            ['rate_count'=>auth()->user()->id],
//            ['rate'=>$rate]
//        );
    }



    public function scopeOrderByRate($query, $orderType='desc') {
        $key_a = $this->getTable() .'.' . $this->getKeyName();
        $key_b = 'rates.rateable_id';
        $class = $this->getMorphClass();

        return $this->leftJoin('rates', function ($join) use ($key_a, $key_b, $class) {
            $join->on($key_a, '=', $key_b)
                ->where('rateable_type', '=', $class);
        })
            ->orderBy('rates.rate', $orderType)
            ->select([$this->getTable().'.*']);
    }



}
