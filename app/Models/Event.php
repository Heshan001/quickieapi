<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'courseName', 'courseOverview', 'courseContent', 'minimumResult', 'subjectStream', 'zCore', 'image', 'institute_id',
    ];


}
