<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Trip extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function targets(){
        return $this->hasMany(Target::class,'trip_id','id');
    }


    public static function boot()
    {
        parent::boot();
        static::deleted(function (Trip $trip) {
            $trip->targets()->delete();
        });
    }
}
