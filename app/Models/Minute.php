<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    use HasFactory;

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
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
