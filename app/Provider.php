<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'url',
        'name'
    ];

//    public function keywords() {
//        return $this->hasMany('App\Keyword');
//    }
}
