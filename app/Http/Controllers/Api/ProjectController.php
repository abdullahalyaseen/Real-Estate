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
        $project = Project::with('flats', 'repres', 'marker', 'photos', 'videos')->where('id', $id)->first();

        return new ProjectResource($project);

    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws \Throwable
     */
    public function createProject(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50', 'unique:projects'],
            'province' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'district' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'under_constructions' => ['required', 'boolean'],
            'move_date' => ['date_format:Y-m-d'],
            'min_price' => ['required', 'numeric', 'digits_between:1,9'],
            'max_price' => ['required', 'numeric', 'digits_between:1,9'],
            'type' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'specifications' => ['required', 'array', 'max:100'],
            'lat' => ['required'],
            'lag' => ['required'],
            'icon_type' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'repres_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'phone_number' => ['required', 'numeric', 'digits_between:10,15',],
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
        MarkerController::createMarker($request->lat, $request->lag, $request->icon_type, $projectId);

        ///Create Representative for Project:
        RepresController::createRepres($request->repres_name, $request->phone_number, $projectId);

        return response(['message' => $project->name . ' ' . 'has been created', 'project_id' => $projectId], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function updateProject(Request $request, $id)
    {
        $request->validate([
            'name' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'province' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'district' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'under_constructions' => ['boolean'],
            'move_date' => ['date_format:Y-m-d'],
            'min_price' => ['numeric', 'digits_between:1,9'],
            'max_price' => ['numeric', 'digits_between:1,9'],
            'type' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:50'],
            'specifications' => ['array', 'max:100'],
        ]);
        $project = Project::find($id);
        $project->update($request->toArray());
        return response(['message' => $project->name . 'has been updated'], 200);
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        $name = $project->name;
        $project->delete();
        return response(['message' => $name . ' ' . 'has been deleted'], 200);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function addProjectVideos(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $request->validate([
            'video.*' => ['required', 'mimes:mp4', 'max:20480'],
        ]);

        $result = VideoController::addProjectVideos($request, $project->id);

        if ($result) {
            return response(['message' => 'Video has been added'], 200);
        } else {
            return response(['message' => 'No thing changed'], 200);
        }

    }


    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function addProjectPhotos(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $request->validate([
            'image.*' => ['required', 'image', 'max:10240']
        ]);

        $result = PhotoController::addProjectPhotos($request, $project->id);
        if ($result) {
            return response(['message' => 'Photos has been added'], 200);
        } else {
            return response(['message' => 'No thing changed'], 200);
        }

    }


}
