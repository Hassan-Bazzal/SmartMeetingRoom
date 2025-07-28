<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function minutes()
    {
        return $this->hasMany(Minute::class);
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
}