<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlatTypeResource;
use App\Models\Flat;
use Illuminate\Http\Request;

class FlatController extends Controller
{
    public function getFlatTypes(){
        return new FlatTypeResource(config('constants.flat_types'));
    }

    public function addFlat(Request $request){
        $request->validate([
            'flat_type'=>['required'],
            'total_meter'=>['required','numeric'],
            'net_meter'=>['required','numeric'],
            'project_id'=>['required','numeric']
        ]);
        if(in_array($request->get('flat_type'),config('constants.flat_types'))){
            $flat = new Flat();
            $flat->flat_type = $request->flat_type;
            $flat->total_meter = $request->total_meter;
            $flat->net_meter = $request->net_meter;
            $flat->project_id = $request->project_id;

            $flat->save();
            return response(['message'=>'Flat has been added'],200);
        }else{
            return response(['message'=>'No such flat type'],404);
        }
    }

    public function deleteFlat($id){
        $flat = Flat::findOrFail($id);

        $flat->delete();
        return response(['message'=>'Flat has been deleted'],200);

    }
}
