<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $guarded = [];
    protected $appends = array('created_at_formatted');

    public function getCreatedAtFormattedAttribute()
    {
        if ($this->created_at)
            return $this->created_at->format('d-m-Y G:i');
    }
    /**
     * Get the user that owns the Claim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function track()
    {
        return $this->belongsTo(Track::class, 'track_id');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'point_id');
    }
}
