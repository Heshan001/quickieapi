<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "error"=>"Invalid email or password"
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'fName' => $user->role === 'student' ? $user->student->fName : ($user->role == 'institute' ? $user->institute->instituteName : ''),
            'lName' => $user->role === 'student' ? $user->student->lName : ($user->role == 'institute' ? '' : ''),
        ]);

    }



    function user(Request $request) {
        return $request->user();

    }

    public function checkUserAuthOrNot()
    {
        $user = null;
        if (auth('sanctum')->check()) {
            $user = User::find(auth('sanctum')->user()->id);
        }
        if ($user) {
            return response()->json([
                'user'=>$user
            ]);
        } else {
            // Use an empty object
            $user = (object)[
                'valid' => false,
            ];
            return response()->json([
                'user' => $user
            ]);
        }
    }

    public function logout($request)
    {
        return $request->user()->currentAccessToken()->delete();
    }

    // public function logout()
    // {
    //     return auth('sanctum')->user()->currentAccessToken()->delete();
    // }


    public function signup(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'fName' => 'required',
                'role' => 'required|in:student,institute,admin',
            ]);

            if ($validator->fails()) {
                return response()->json([
                        'code'=>400,
                        'status'=>false,
                        'message'=>'Validation Error',
                        'errors' => $validator->errors(),
                    ]
                    , 400);
            }

            $user = new User([
                'email' => $request->email,
                'name' => $request->role === 'student' ? $request->fName . ' ' . $request->lName : $request->instituteName,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            $user->save();

            // Create student or institute based on the role
            if ($request->role === 'student') {
                $user->student()->create([
                    'fName' => $request->fName,
                    'lName' => $request->lName,
                ]);
            } elseif ($request->role === 'institute') {
                $user->institute()->create([
                    'instituteName' => $request->instituteName,
                ]);
            }

            $token = $user->createToken('token')->plainTextToken;

            return response()->json(
                [
                    "code"=>200,
                    "data"=>(object)[
                        'token' => $token,
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'fName' => isset($request->fName)?$request->fName:'',
                        'lName' => isset($request->lName)?$request->lName:'',
                        'instituteName' => isset($request->instituteName)?$request->instituteName:'',
                        'role' => $request->role,
                    ],
                    "status"=>'true',
                    "message"=>"success"
                ]
        );
        }


}
