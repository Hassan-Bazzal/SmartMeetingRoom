<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    protected $fillable = [
        'booking_id',
        'created_by',
        'content',
        'status',
        'due_date',
        'notes',
        'assigned_to',
        
    ];
}
