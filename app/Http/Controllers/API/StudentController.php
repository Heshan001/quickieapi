<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // registering student
    public function store(Request $request)
    {
         $request->validate([
            'fName' => 'required|min:3',
            'lName' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        // creating a student
       $user =  User::create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => 'student'
        ]);

        //save any additional details
        $user->extras()->createMany([
            ['key' => 'first_name' , 'value' => $request->input('fName')],
            ['key' => 'last_name' , 'value' => $request->input('lName')],
        ]);

        return response()->json([
            'message' => 'Student Added Successfully'
        ]);
    }

     // login student
    public function auth(Request $request)
    {
        // validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // find user with email
        $user = User::where('email',$request->input('email'))->first();

        // checking password & email
        if($user && Hash::check($request->input('password'),$user->password)){
            // successfully logged in

            // generate token and return to API
            $token = auth()->user()->createToken('default')->plainTextToken;

            return response()->json([
                'message' => 'Success',
                'token' => $token
            ]);
        }

        // invalid email or pass
        return response()->json([
            'message' => 'Invalid email or password'
        ],422);
    }
}
