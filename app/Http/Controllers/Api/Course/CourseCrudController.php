<?php

namespace App\Http\Controllers\Api\Course;


use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseCrudController extends Controller
{
    use AuthUserHelper;

    public function store(Request $request){
        // validation
        $authUser = $this->getAuthUser();
        if($authUser->role == 'institute'){
            $data = [
                "name"=>$request->name,
                "overview"=>$request->overview,
                "institute_id"=> $authUser->institute->id
            ];
            $course = new Course($data);
            $course =$course->save();

            return response()->json([
                "code" => 200,
                "data" => [],
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

    public function delete(Request $request, $course_id){

        if($course_id){
           // $course = Course::find($course_id);
            $status = Course::destroy($course_id);
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
            'message' => 'Error',
            'error' => "Error",
        ], 400);

    }

    public function instituteCourseList(Request $request){
        //$request['query'] = $request->query('query');
        $request['limit'] = $request->query('limit');
        $request['page'] = $request->query('page');
        $authUser = $this->getAuthUser();

        $data = Course::where('institute_id', $authUser->institute->id)->paginate($request['limit']);

        $out = [
            "courses" => $data->items(),
            "pagination" => [
                "total" => $data->total(),
                "per_page" => $data->perPage(),
                "current_page" => $data->currentPage(),
            ] 
        ];

        return response()->json([
            "code" => 200,
            "data" => $out,
            "status" => 'true',
            "message" => "success"
        ]);
    }




}
