<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snap extends Model
{
    public function Publisher() {
        return $this->belongsTo('App\Publisher');
    }
    public function Channels() {
        return $this->hasMany('App\Channel');
    }
}
