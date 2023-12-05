<?php

namespace App\Http\Controllers\Api\News;


use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsCrudController extends Controller
{
    use AuthUserHelper;

    public function store(Request $request){
        // validation
        $authUser = $this->getAuthUser();
        if($authUser->role == 'admin'){
            $data = [
                "title"=>$request->title,
                "description"=>$request->description,
                "image"=>$request->image,
            ];



               // Save the image file
                $image = $request->file('image');
            //    // $imageName = $image->getClientOriginalName();

            //  $file = $request->file('file');

                $uploaded = Storage::disk('public')->put('news', $request->file('image'));




                $path = 'storage/' . $uploaded;

            //     // Update the 'image' property in the data array
                $data['image'] = $path;


            $news = new News($data);
            $news =$news->save();

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

    public function delete(Request $request, $news_id){

        if($news_id){
           // $news = News::find($news_id);
            $status = News::destroy($news_id);
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
            'message' => 'Empty data array',
            'error' => "Error",
        ], 400);

    }

    public function NewsList(Request $request)
{
    $request['limit'] = $request->query('limit');
    $request['page'] = $request->query('page');

    $data = News::paginate($request['limit']);

    $out = [
        "news" => $data->items(),
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

public function AllNewsList(Request $request)
    {

            $request['limit'] = $request->query('limit');
            $request['page'] = $request->query('page');


        $data = News::paginate( $request['limit']);

        $out = [
            "news" => $data->items(),
            // "pagination" => [
            //     "total" => $data->total(),
            //     "per_page" => $data->perPage(),
            //     "current_page" => $data->currentPage(),
            // ]
        ];

        return response()->json([
            "code" => 200,
            "data" => $out,
            "status" => 'true',
            "message" => "success"
        ]);
    }



}
