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
                "error"=>"Password not matched"
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'first_name' => $user->role === 'student' ? $user->student->first_name : ($user->role=='institute'? $user->institute->first_name : ''  ),
            'last_name' => $user->role === 'student' ? $user->student->last_name : ($user->role=='institute'? $user->institute->last_name : ''  ),
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
            // Use an empty object instead of stdClass
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

    public function signup(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'first_name' => 'required',
                'role' => 'required|in:student,institute,admin',
            ]);

            if ($validator->fails()) {
                return response()->json([
                        'code'=>400,
                        'errors' => $validator->errors(),
                        'status'=>false,
                        'message'=>"Validation Error",
                    ]
                    , 400);
            }

            $user = new User([
                'email' => $request->email,
                'name' => $request->first_name.' '.$request->last_name,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            $user->save();

            // Create student or institute based on the role
            if ($request->role === 'student') {
                $user->student()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);
            } elseif ($request->role === 'institute') {
                $user->institute()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
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
                        'first_name' => isset($request->first_name)?$request->first_name:'',
                        'last_name' => $request->last_name,
                        'role' => $request->role,
                    ],
                    "status"=>'success',
                    "message"=>"success"
                ]
        );
        }


}
