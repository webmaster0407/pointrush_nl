<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $guarded = [];
    protected $casts = [
        'transparant' => 'boolean'
    ];

    public $timestamps = false;

    public function track()
    {
        return $this->belongsTo('App\Track');
    }
}
