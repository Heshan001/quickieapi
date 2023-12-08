<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;


class UserController extends Controller
{
    use AuthUserHelper;

    public function getAllUsersWithEmailsAndRoles(Request $request)
    {
        // Check if user is an admin
        if ($this->getAuthUser()->role !== 'admin') {
            return response()->json([
                'code' => 403,
                'status' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        // Validate and handle pagination parameters
        $request->validate([
            'limit' => 'nullable|integer|max:100',
            'page' => 'nullable|integer|min:1',
            'query' => 'nullable|string',
        ]);

        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $query = $request->query('query', '');

        // Build the user query
        $data = User::select('id', 'email', 'role',)->paginate($request['limit']);



        // Prepare response data
        $out = [
            "users" => $data->items(),
        ];

            // 'pagination' => [
            //     'total' => $users->total(),
            //     'per_page' => $users->perPage(),
            //     'current_page' => $users->currentPage(),
            //     'last_page' => $users->lastPage(),
            // ],


        // Return successful response
        return response()->json([
            'code' => 200,
            'data' => $out,
            'status' => true,
            'message' => 'User details retrieved successfully',
        ], 200);
    }
}

