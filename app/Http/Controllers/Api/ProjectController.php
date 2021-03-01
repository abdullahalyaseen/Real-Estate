<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectsResource;
use App\Models\Project;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class ProjectController extends Controller
{
    /**
     * @return ProjectsResource
     */
    public function getAllProjects()
    {
        $projects = Project::paginate(10);
        return new ProjectsResource($projects);
    }

    /**
     * @param $id
     * @return ProjectResource
     */
    public function getProjectById($id)
    {
        $project = Project::with('flats', 'repres', 'marker','photos:id,url')->where('id', $id)->first();
        return new ProjectResource($project);
    }

    public function createProject(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'province' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'district' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'under_constructions' => ['required', 'boolean'],
            'move_date' => ['date_format:Y-m-d'],
            'min_price' => ['required', 'numeric', 'digits_between:1,9'],
            'max_price' => ['required', 'numeric', 'digits_between:1,9'],
            'type' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'specifications' => ['required', 'array', 'max:100'],
            'lat' => ['required', 'regex:/^\d+(\.\d{1,100})?$/'],
            'lag' => ['required', 'regex:/^\d+(\.\d{1,100})?$/'],
            'icon_type' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'repres_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'phone_number' => ['required', 'numeric','digits_between:10,15',],
        ]);


        ///Create project:
        $project = new Project();
        ///Assign values to new project:
        $project->name = $request->name;
        $project->province = $request->province;
        $project->district = $request->district;
        $project->under_constructions = boolval($request->under_constructions);
        $project->min_price = $request->min_price;
        $project->max_price = $request->max_price;
        $project->type = $request->type;
        $project->specifications = json_encode($request->specifications);

        ///Save project and get PROJECT ID:
        $project->saveOrFail();
        $projectId = $project->id;


        ///Create Marker for location:
        MarkerController::createMarker($request->lat,$request->lag,$request->icon_type,$projectId);

        ///Create Representative for Project:
        RepresController::createRepres($request->repres_name,$request->phone_number,$projectId);

        return response(['message'=>$project->name .' '. 'has been created'],200);
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function deleteProject($id){
        $project = Project::findOrFail($id);
        $name = $project->name;
        $project->delete();
        return response(['message'=>$name .' '. 'has been deleted'],200);
    }


    public function addProjectPhotos(Request $request, $id){
        $project = Project::findOrFail($id);
        $request->validate([
            'files'=>['required','image','max:10240']
        ]);

        return PhotoController::addProjectPhotos($request,$project->id);

    }


    public function updateProject(Request $request, $id){
        $request->validate([
            'name' => [ 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'province' => [ 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'district' => [ 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'under_constructions' => [ 'boolean'],
            'move_date' => ['date_format:Y-m-d'],
            'min_price' => [ 'numeric', 'digits_between:1,9'],
            'max_price' => [ 'numeric', 'digits_between:1,9'],
            'type' => [ 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'specifications' => [ 'array', 'max:100'],
        ]);
        $project = Project::find($id);
        $project->update($request->toArray());
        return response(['message'=>$project->name.'has been updated'],200);
    }
}
