<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{

    use HasFactory;

    public function marker()
    {
        return $this->hasOne(Marker::class, 'project_id', 'id');
    }

    public function flats()
    {
        return $this->hasMany(Flat::class, 'project_id', 'id');
    }

    public function repres()
    {
        return $this->hasMany(Repres::class, 'project_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'project_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'project_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function (Project $project) {
            $id = $project->id;
            Storage::deleteDirectory('/images/projects/'.$id.'/');
            $project->marker()->delete();
            $project->photos()->delete();
            $project->videos()->delete();
            $project->flats()->delete();
            $project->repres()->delete();

        });
    }
}
