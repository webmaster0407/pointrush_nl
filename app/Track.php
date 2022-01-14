<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'title',
        'hide_menu_bar',
        'visitor',
        'show_log_public',
        'user_id',
    ];

    public function points()
    {
        return $this->hasMany(Point::class, 'track');
    }
}
