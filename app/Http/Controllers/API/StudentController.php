<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $student = new Student;
        $student->fName = $request->input('fName');
        $student->lName = $request->input('lName');
        $student->email = $request->input('email');
        $student->password = $request->input('password');
        $student->save();

        return response()->json([
            'status' => 200,
            'message' => 'Student Added Successfully',
        ]);
    }
}
