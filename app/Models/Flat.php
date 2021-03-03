<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;


    protected $hidden = [
        'created_at',
        'updated_at',
        'project_id'
    ];


    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
