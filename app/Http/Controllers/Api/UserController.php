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
        return new UserResource(User::where('id','=',$id)->with('departments')->get());
    }

    /**
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'last_name' => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'email' => ['email', 'required', 'unique:users', 'max:64'],
            'password' => ['required', 'max:32', 'confirmed'],
            'role' => ['required', 'max:5'],
            'number' => ['required', 'digits_between:10,15',],
        ]);

        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->number = $request->number;

        $user->save();
        return $user;
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

      if(Gate::allows('edit_user')){
          $user = User::findOrFail($id);
          $requesrUser = User::where('email', '=', $data['email'])->get('id')->toArray();
          if (count($requesrUser) != 0) {
              $requestId = $requesrUser[0]['id'];
              if ($requestId == $id) {
                  return $this->update($user,$data);
              } else {
                  return response(['message'=>'used email'],405);
              }
          }else{
              return $this->update($user,$data);
          }
      }

//        $userEmail = User::find($id)->get('email')[0]->email;

//        User::findOrFail($id)->update($data);
//        return response(['message' => 'User has been updated'], 200);

    }


    /**
     * @param Request $request
     * @param $id
     */
    public function deleteUser($id)
    {
        if (User::find($id)) {
            User::find($id)->delete();
            return response(['message' => 'User has been deleted'], 200);
        }
        return response(['messages' => 'No user to delete'], 400);
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
    private function update (User $user , $data){
        //take the new department name from data sent to API
        $roleTitle = $data['role'];
        //get the original department the user in
        $userOriginalDepartmentId = $user->departments()->first()->id;
        //find the new department id
        $newRoleId = Department::where('title','=',$roleTitle)->first('id')['id'];
        //find the original department the user in and update the pivot table with
        //new department_id for that user id
        Department::find($userOriginalDepartmentId)->users()->updateExistingPivot($user->id,['department_id'=>$newRoleId],true);

        unset($data['role']);
        return $user->update($data);

    }
}
