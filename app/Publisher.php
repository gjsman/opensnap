<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    public function Snaps() {
        return $this->hasMany('App\Snap');
    }
}
