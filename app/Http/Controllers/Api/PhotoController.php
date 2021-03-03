<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public static function addProjectPhotos(Request $request , $projectId){
        $counter = 0;
        $files = $request->file('image');
        foreach ($files as $file){
            $fileName = time().'-'.str_shuffle('qwertyuiopasdfg').'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('/images/projects/'.$projectId,$fileName);
            $photo = new Photo();
            $photo->path = $path;
            $photo->url = env('APP_URL').'/'.$path;
            $photo->project_id = $projectId;
            $photo->save();
            $counter++;
        }

        if($counter > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deletePhoto($id){
        $photo = Photo::find($id);
        $path = $photo->path;

        Storage::delete($path);
        $photo->delete();
        return response(['message'=>'Photo has been deleted'],200);
    }
}
