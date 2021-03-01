<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarkersResource;
use App\Models\Marker;



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
}
