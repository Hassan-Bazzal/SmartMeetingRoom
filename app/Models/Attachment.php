<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class,'uploaded_by');
    }
    public function minute()
    {
        return $this->belongsTo(Minute::class);
    }

    protected $fillable = ['file_path', 'file_name', 'uploaded_by', 'minute_id'];
}
