<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
}
