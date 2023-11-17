<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function store(Request $request)
    {
        $institute = new Institute;
        $institute->instituteName = $request->input('instituteName');
        $institute->email = $request->input('email');
        $institute->password = $request->input('password');
        $institute->save();

        return response()->json([
            'status' => 200,
            'message' => 'Institute Added Successfully',
        ]);
    }
}
