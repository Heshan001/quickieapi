<?php

namespace App\Http\Controllers\Api\Event;


use App\Helpers\AuthUserHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventCrudController extends Controller
{
    use AuthUserHelper;

    public function store(Request $request){
        // validation
        $authUser = $this->getAuthUser();
        if($authUser->role == 'institute'){
            $data = [
                "eventName"=>$request->eventName,
                "eventDescription"=>$request->eventDescription,
                "image"=>$request->image,
                "institute_id"=> $authUser->institute->id
            ];



               // Save the image file
                $image = $request->file('image');
            //    // $imageName = $image->getClientOriginalName();

            //  $file = $request->file('file');

                $uploaded = Storage::disk('public')->put('events', $request->file('image'));




                $path = 'storage/' . $uploaded;

            //     // Update the 'image' property in the data array
                $data['image'] = $path;


            $event = new Event($data);
            $event =$event->save();

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

    public function delete(Request $request, $event_id){

        if($event_id){
           // $event = Event::find($event_id);
            $status = Event::destroy($event_id);
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

    public function instituteEventList(Request $request){
        //$request['query'] = $request->query('query');
        $request['limit'] = $request->query('limit');
        $request['page'] = $request->query('page');
        $authUser = $this->getAuthUser();

        $data = Event::where('institute_id', $authUser->institute->id)->paginate($request['limit']);

        $out = [
            "events" =>$data->items(),
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
