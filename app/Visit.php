<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
//    protected $fillable = array();

    public function link() {
        return $this->belongsTo('App\Link');
    }
}
