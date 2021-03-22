<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    public function trip(){
        return $this->belongsTo(Trip::class,'id','trip_id');
    }

    public function project(){
        return $this->belongsTo(Project::class,'id','project_id');
    }
}
