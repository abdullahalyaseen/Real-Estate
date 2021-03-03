<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public static function addProjectVideos(Request $request , $projectId){
        $counter = 0;
        $files = $request->file('video');
        foreach ($files as $file){
            $fileName = time().'-'.str_shuffle('qwertyuiopasdfg').'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('/videos/projects/'.$projectId,$fileName);
            $video = new Video();
            $video->path = $path;
            $video->url = env('APP_URL').'/'.$path;
            $video->project_id = $projectId;
            $video->save();
            $counter++;
        }

        if($counter > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteVideo($id){
        $video = Video::find($id);
        $path = $video->path;

        Storage::delete($path);
        $video->delete();
        return response(['message'=>'video has been deleted'],200);
    }
}
