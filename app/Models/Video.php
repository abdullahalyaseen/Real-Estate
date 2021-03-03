<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;



    protected $hidden = [
        'created_at',
        'updated_at',
        'path',
        'project_id'
    ];


    /**
     * @return BelongsTo
     */
    public function project(){
        return $this->belongsTo(Project::class,'id','project_id');
    }
}
