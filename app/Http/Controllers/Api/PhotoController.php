<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public static function addProjectPhotos(Request $request , $projectId){
        $files = $request->allFiles();
        foreach ($files as $file){
            $path = $file->store('/images/projects/'.$projectId);
            $photo = new Photo();
            $photo->url = $path;
            $photo->project_id = $projectId;
            $photo->save();
        }

        return Photo::where('project_id', '=', $projectId)->get();
    }

    public function deletePhoto($id){
        $photo = Photo::find($id);
        $path = $photo->url;

        Storage::delete($path);
        $photo->delete();
        return response(['message'=>'Photo has been deleted'],200);
    }
}
