<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'fName',
        'lName',
        'user_id',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'user_id');
    }

}
