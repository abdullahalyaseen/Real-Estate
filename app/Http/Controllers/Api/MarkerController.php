<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarkersResource;
use App\Models\Marker;
use App\Models\Repres;
use Illuminate\Http\Request;


class MarkerController extends Controller
{
    public function getAllMarkers(){
        $markers= Marker::with('project:id,name')->get();
        return new MarkersResource($markers);
    }

    public static function createMarker($lat,$lag,$iconType,$projectId){
        $marker = new Marker();
        ///Assign values to new Marker:
        $marker->lat = $lat;
        $marker->lag = $lag;
        $marker->icon_type = $iconType;
        $marker->project_id = $projectId;
        ///Save Marker:
        $marker->save();
    }

    public function updateMarker(Request $request,$id){
        $request->validate([
            'icon_type' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/','max:20'],
        ]);

        Marker::find($id)->update($request->toArray());
        return response(['message'=>'Marker has been updated'],200);
    }

}
