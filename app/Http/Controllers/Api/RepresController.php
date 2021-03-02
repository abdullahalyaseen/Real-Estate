<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Repres;
use Illuminate\Http\Request;


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

    public function updateRepres(Request $request, $id){
        $request->validate([
            'repres_name'=> ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/','max:50'],
            'phone_number'=>['numeric','digits_between:10,15'],
        ]);
        $repres = Repres::findOrFail($id);
        $repres->update($request->toArray());
        return response(['message'=>'Representative has been updated'],200);
    }
}
