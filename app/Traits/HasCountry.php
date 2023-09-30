<?php

namespace App\Traits;
use App\Models\Country;
use App\Models\State;

trait HasCountry
{
    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state(){
        return $this->belongsTo(State::class, 'state_id');
    }
}
