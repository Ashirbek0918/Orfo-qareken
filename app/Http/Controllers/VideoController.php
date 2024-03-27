<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\video;
use App\Jobs\SaveToWords;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Jobs\ArrayToWords;

class VideoController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "video" => 'required',
            'application' => 'required'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $video_url = [];
        $video = $request->file('video');
        $video_name = $video->getclientOriginalname();
        $video->move(public_path('/videos'), $video_name);
        $video_url[] = env('APP_URL')."/public/videos/".$video_name;
        $application_url = [];
        $application = $request->file('application');
        $application_name = $application->getclientOriginalname();
        $application->move(public_path('/applications'), $application_name);
        $application_url[] = env('APP_URL')."/public/applications/".$application_name;
        $file = video::create([
            'video_name' => $video_name,
            'application_name' => $application_name,
            'video_url' => $video_url[0],
            'application_url' =>$application_url[0],
        ]);
        return $file;
    }
        
    public function download(){
        $video = Video::first();
        $name = $video->application_name;
        return response()->download(public_path("/applications/$name"),"$name");
    }

}
