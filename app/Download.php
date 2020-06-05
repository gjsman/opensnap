<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public function Channel() {
        return $this->belongsTo('App\Channel');
    }
}
