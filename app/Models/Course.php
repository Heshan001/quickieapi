<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable =[
        'courseName ','courseOverview','courseContent','minimumResult','subjectStream','zCore','image',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

}
