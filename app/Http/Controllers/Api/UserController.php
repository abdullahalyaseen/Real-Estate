<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\Department;
use App\Models\Permission;
use App\Models\User;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function getAllUsers()
    {
        if (Gate::allows('view_users')) {
            return new UsersResource(User::with('departments:title')->paginate(10, ['id', 'first_name', 'last_name', 'is_active']));
        } else {
            return response(['message' => 'Not Allowed'], 405);
        }
    }

    public function getUser($id)
    {
        if(Gate::allows('view_users')){
            $response = User::with('departments')->where('id', '=', $id)->get();

            if ($response->count() != 0) {
                return new UserResource($response);
            } else {
                return response([], 404);
            }
        }
    }

    /**
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'last_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'email' => ['email', 'required', 'unique:users', 'max:32'],
            'password' => ['required', 'max:64', 'min:8', 'confirmed'],
            'role' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'exists:departments,title'],
            'number' => ['required', 'regex:/^\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/'],
        ]);

        if (Gate::allows('add_user')) {


            $user = new User();

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->number = $request->number;
            $user->is_active = $request->is_active;
            $user->save();

            $userId = $user->id;
            //take the department name from data sent to API
            $roleTitle = $data['role'];
            //find the department id
            $roleId = Department::where('title', '=', $roleTitle)->first('id')['id'];
            //TODO: Link between user and department

            $user->departments()->attach($roleId, ['user_id' => $userId]);

            return $user;
        }
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function updateUser(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'last_name' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'email' => ['email', 'max:32'],
            'role' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'exists:departments,title'],
            'number' => ['regex:/^\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/'],
            'is_active' => ['boolean']
        ]);

        ///Using ->update to update any field optionally and according to fillable
        /// in User model

        if (Gate::allows('edit_user')) {
            $user = User::findOrFail($id);
            $requesrUser = User::where('email', '=', $data['email'])->get('id')->toArray();
            if (count($requesrUser) != 0) {
                $requestId = $requesrUser[0]['id'];
                if ($requestId == $id) {
                    return $this->update($user, $data);
                } else {
                    return response(['message' => 'used email'], 405);
                }
            } else {
                return $this->update($user, $data);
            }
        }
    }

    public function changePassword(Request $request, $id)
    {
        if (Gate::allows('edit_user')) {
            $data = $request->validate([
                'password' => ['required', 'max:64', 'min:8', 'confirmed'],
            ]);
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->get('password'));
            return response(['message' => 'Password changed'], 200);
        }
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function deleteUser($id)
    {
        if (Gate::allows('delete_user')) {
            if (User::find($id)) {
                $user = User::find($id);
                $userDepartmentId = $user->departments()->first()->id;
                $user->departments()->detach($userDepartmentId, ['user_id' => $id]);
                $user->delete();
                return response(['message' => 'User has been deleted'], 200);
            }
            return response(['messages' => 'No user to delete'], 400);
        } else {
            return response(['message' => 'you don\'t have ability to delete this user'], 400);
        }
    }

    public function getAbilities(Request $request)
    {
        $user = $request->user();
        $departments = $user->departments->pluck('id');
        $departmentArray = array();
        foreach ($departments as $department) {
            $permissions = Department::find($department)->permissions->pluck('title');
            foreach ($permissions as $permission) {
                Array_push($departmentArray, $permission);
            }
        }
        return $departmentArray;
//
    }


    /**
     * @param User $user
     * @param $data
     * @return mixed
     */
    private function update(User $user, $data)
    {
        //take the new department name from data sent to API
        $roleTitle = $data['role'];
        //get the original department the user in
        $userOriginalDepartmentId = $user->departments()->first()->id;
        //find the new department id
        $newRoleId = Department::where('title', '=', $roleTitle)->first('id')['id'];
        //find the original department the user in and update the pivot table with
        //new department_id for that user id
        Department::find($userOriginalDepartmentId)->users()->updateExistingPivot($user->id, ['department_id' => $newRoleId], true);

        unset($data['role']);
        return $user->update($data);

    }
}
