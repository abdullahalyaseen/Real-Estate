<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{

    protected $fillable=[
        'lat',
        'lag',
        'icon_type',
    ];
    use HasFactory;

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
