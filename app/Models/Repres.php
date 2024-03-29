<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repres extends Model
{
    use HasFactory;

    protected $fillable=[
        'repres_name',
        'phone_number',
    ];

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
