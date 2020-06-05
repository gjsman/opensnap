<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function Download() {
        return $this->hasOne('App\Download');
    }
    public function Snap() {
        return $this->belongsTo('App\Snap');
    }
}
