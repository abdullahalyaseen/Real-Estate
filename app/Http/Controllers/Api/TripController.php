<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripsResource;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function getAllTrips(){
        $trips= Trip::with('customer','user','targets')->paginate(10);
        return new TripsResource($trips);
    }
}
