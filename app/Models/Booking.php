<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
    public function minutes()
    {        return $this->hasMany(Minute::class);
    }
    protected $fillable = ['room_id', 'user_id', 'start_time', 'end_time', 'status', 'agenda', 'title'];
}
