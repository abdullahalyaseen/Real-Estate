<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Repres;

class RepresController extends Controller
{
    public static function createRepres($represName, $phoneNumber, $projectId){
        $repres = new Repres();
        ///Assign values to new Representative:
        $repres->repres_name = $represName;
        $repres->phone_number = $phoneNumber;
        $repres->project_id = $projectId;
        ///Save Representative:
        $repres->save();
    }
}
