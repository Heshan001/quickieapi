<?php

namespace App\Http\Controllers\Api\Course;


use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
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
                "courseName"=>$request->courseName,
                "courseOverview"=>$request->courseOverview,
                "courseContent"=>$request->courseContent,
                "minimumResult"=>$request->minimumResult,
                "subjectStream"=>$request->subjectStream,
                "zCore"=>$request->zCore,
                "image"=>$request->image,
                "institute_id"=> $authUser->institute->id
            ];

                // Save the image file
                $image = $request->file('image');
             //   $imageName = $image->getClientOriginalName();
               // $path = public_path('uploads/' . $imageName );
               // $image->move($path);
               // Get the file from the request
                $file = $request->file('file');
                // Store the file in the public/uploads directory
                    // $fileName = $file->getClientOriginalName();
                     $uploaded = Storage::disk('public')->put('courses', $request->file('image'));
                     $path = 'storage/' . $uploaded;

                // Update the 'image' property in the data array
                $data['image'] = $path;


            $course = new Course($data);
            $course =$course->save();

            return response()->json([
                "code" => 200,
                "data" => $data,
                "status" => 'true',
                "message" => "success"
            ]);
        }

        return response()->json([
            'code' => 401,
            'status' => false,
            'message' => 'Error',
            'error' => "Error",
        ], 401);

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

    public function instituteCourseList(Request $request)
    {
        $request['query'] = $request->query('query');
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

        $filteredCourses = [];
        foreach ($out['courses'] as $course) {
            $filteredCourse = [
                'courseName' => $course->courseName,
                'courseOverview' => $course->courseOverview,
                'courseContent' => $course->courseContent,
                'image' => $course->image,
            ];
            $filteredCourses[] = $filteredCourse;
        }

        $out['courses'] = $filteredCourses;

        return response()->json([
            "code" => 200,
            "data" => $out,
            "status" => 'true',
            "message" => "success"
        ]);
    }

    // public function instituteCourseList(Request $request){
    //     //$request['query'] = $request->query('query');
    //     $request['limit'] = $request->query('limit');
    //     $request['page'] = $request->query('page');
    //     $authUser = $this->getAuthUser();

    //     $data = Course::where('institute_id', $authUser->institute->id)->paginate($request['limit']);


    //     $out = [
    //         "courses" => $data->items(),
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



    public function AllInstituteCourseList(Request $request)
{

    $request['limit'] = $request->query('limit');
    $request['page'] = $request->query('page');

    $data = Course::select('courseName', 'courseOverview', 'courseContent', 'image')
        ->paginate($request['limit']);

    $out = [
        "courses" => $data->items(),
    ];

    return response()->json([
        "code" => 200,
        "data" => $out,
        "status" => 'true',
        "message" => "success"
    ]);
}
// public function AllInstituteCourseList(Request $request)
// {

//         $request['limit'] = $request->query('limit');
//         $request['page'] = $request->query('page');


//     $data = Course::paginate( $request['limit']);



//     $out = [
//         "courses" => $data->items(),
//         // "pagination" => [
//         //     "total" => $data->total(),
//         //     "per_page" => $data->perPage(),
//         //     "current_page" => $data->currentPage(),
//         // ]
//     ];

//     return response()->json([
//         "code" => 200,
//         "data" => $out,
//         "status" => 'true',
//         "message" => "success"
//     ]);
// }




public function oneCourseDetails(Request $request)
{
    $courseId = $request->query('course_id');

    // Validate required parameters
    if (!$courseId) {
        return response()->json([
            'code' => 400,
            'status' => false,
            'message' => 'Missing course_id parameter',
        ], 400);
    }

    // Find the course using the ID
    $course = Course::find($courseId);

    // Check if course exists
    if (!$course) {
        return response()->json([
            'code' => 404,
            'status' => false,
            'message' => 'Course not found',
        ], 404);
    }

    // Filter and prepare the course details
    $filteredCourse = [
        'id' => $course->id,
        'courseName' => $course->courseName,
        'courseOverview' => $course->courseOverview,
        'courseContent' => $course->courseContent,
        'image' => $course->image,
        // Add additional fields you want to include
    ];

    // Return the course details
    return response()->json([
        'code' => 200,
        'data' => $filteredCourse,
        'status' => 'true',
        'message' => 'Course details',
    ], 200);
}


public function AllInstituteCourseListWithFilters(Request $request)
{

    $zScore = $request->zScore;
    $stream = $request->stream;
    $result = $request->result;

    $data = Course::select('courseName', 'courseOverview', 'courseContent', 'image','zCore','minimumResult')
    ->where('zCore','>',$zScore)
   
    ->get();

    return response()->json([
        "code" => 200,
        "data" => $data,
        "status" => 'true',
        "message" => "success"
    ]);
}

}
