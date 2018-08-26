<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
        protected $fillable = array('url_origin');

    public function visits() {
        return $this->hasMany('App\Visit', 'link_id', 'id');
    }
}
