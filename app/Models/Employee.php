<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER  = 'user';
    public const ROLE_GUEST = 'guest';

 public function attendees()
{
    return $this->hasMany(Attendee::class, 'user_id'); // specify FK if not standard
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