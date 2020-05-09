<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = [
        'name',
        'providerId',
        'count_all',
        'rocks',
        'sucks'
    ];

    public function provider() {
        return $this->belongsTo('App\Provider', 'providerId', 'id');
    }

    public function score() {
        return round($this->rocks / $this->count_all, 2);
    }

}
