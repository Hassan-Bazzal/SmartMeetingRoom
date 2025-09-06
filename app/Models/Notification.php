<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id');
    }
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
    ];
}
