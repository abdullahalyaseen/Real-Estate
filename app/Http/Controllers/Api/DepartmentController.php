<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentsResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(){
        $titlePerDepartment = Department::all(['title']);
        foreach($titlePerDepartment as $department){
            $titles[] = $department->title;
        }
        return $titles;
    }
}
