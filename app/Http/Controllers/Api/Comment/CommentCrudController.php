<?php

namespace App\Http\Controllers\Api\Comment;


use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentCrudController extends Controller
{
    use AuthUserHelper;

    public function store(Request $request){
        // validation
        $authUser = $this->getAuthUser();
        if($authUser->role == 'student'){
            $data = [
                "student_id"=> $authUser->student->id,
                "comment"=>$request->comment,


            ];

            $comment = new Comment($data);
            $comment =$comment->save();

            return response()->json([
                "code" => 200,
                "data" => $data,
                "status" => 'true',
                "message" => "success"
            ]);
        }

        return response()->json([
            'code' => 400,
            'status' => false,
            'message' => 'Error',
            'error' => "Error",
        ], 400);

    }

    public function delete(Request $request, $comment_id){

        if($comment_id){
           // $comment = Comment::find($comment_id);
            $status = Comment::destroy($comment_id);
            if($status){
                return response()->json([
                    "code" => 200,
                    "data" => [],
                    "status" => 'true',
                    "message" => "success"
                ]);
            }

        }
        return response()->json([
            'code' => 400,
            'status' => false,
            'message' => 'no comment to delete',
            'error' => "Error",
        ], 400);

    }

    // public function CommentList(Request $request){
    //     $request['query'] = $request->query('query');
    //     $request['limit'] = $request->query('limit');
    //     $request['page'] = $request->query('page');
    //     $authUser = $this->getAuthUser();

    //     $data = Comment::where('student_id', $authUser->student->id)->paginate($request['limit']);

    //     $out = [
    //         "comments" =>$data->items(),
    //         "pagination" => [
    //         "total" => $data->total(),
    //         "per_page" => $data->perPage(),
    //         "current_page" => $data->currentPage(),
    //         ]
    //     ];

    //     return response()->json([
    //         "code" => 200,
    //         "data" => $out,
    //         "status" => 'true',
    //         "message" => "success"
    //     ]);
    // }
// to get all comments
    public function AllCommentList(Request $request)
    {

            $request['limit'] = $request->query('limit');
            $request['page'] = $request->query('page');


        $data = Comment::paginate( $request['limit']);

        $out = [
            "comments" => $data->items(),

        ];

        return response()->json([
            "code" => 200,
            "data" => $out,
            "status" => 'true',
            "message" => "success"
        ]);
    }


}
