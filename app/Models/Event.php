<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'eventName', 'institute_id', 'eventDescription', 'Image',
    ];


    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }
}
