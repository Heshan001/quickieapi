<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;

    protected $fillable = [
        'instituteName',
        'user_id',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'user_id');
    }

}
