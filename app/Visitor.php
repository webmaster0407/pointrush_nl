<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'track_id',
    ];

    public function track()
    {
        return $this->belongsTo(Point::class);
    }

    public function scopeReady($query)
    {
        return $query->where('ip_address', request()->ip())
            ->where('created_at', '>', Carbon::now()->subDay()->toDateTimeString());
    }
}
