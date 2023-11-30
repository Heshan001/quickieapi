<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

trait AuthUserHelper
{
    public function getAuthUser()
    {
       return Auth::user();
    }

}
