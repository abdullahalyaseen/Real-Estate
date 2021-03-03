<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripsResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function getAllTrips(){
        $trips= Trip::with('customer:id,name','user:id,first_name,last_name','targets')->paginate(10);
        return new TripsResource($trips);
    }

    public function newTrip(Request $request){
        $request->validate([
            'user_id'=>['required','numeric'],
            'customer_id'=>['required','numeric'],
        ]);

        $trip = new Trip();

        $trip->user_id = $request->user_id;
        $trip->customer_id = $request->customer_id;

        $trip->save();
        return $trip;
    }
}
