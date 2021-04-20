<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if(!app()->runningInConsole()&&$user){
            $departments = Department::with('permissions')->get();

            foreach ($departments as $department){
                foreach ($department->permissions as $permissions){
                    $permissionsArray[$permissions->title][] = $department->id;
                }
            }

            foreach ($permissionsArray as $title => $departments){
                Gate::define($title, function (User $user) use ($departments){
                    return count(array_intersect($user->departments->pluck('id')->toArray(),$departments)) > 0;
                });
            }

        }
        return $next($request);
    }
}
