<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalkAttendee extends Model
{
    protected $fillable = ['talk_id', 'attendee_id'];

    public function talk() {
        return $this->belongsTo(Talk::class, 'talk_id');
    }

    public function attendee() {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }
}
