<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function getAllUsers()
    {
        return new UsersResource(User::get(['id','first_name','last_name','role','is_active']));

    }

    public function getUser($id){
        return new UserResource(User::findOrFail($id));
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
            'password' => ['required', 'max:64', 'confirmed'],
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
        $request->validate([
            'first_name' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'last_name' => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/', 'max:20'],
            'email' => ['email', 'unique:users', 'max:64'],
            'password' => ['max:64', 'confirmed'],
            'role' => ['max:5'],
            'number' => ['digits_between:10,15',],
        ]);
        ///Convert $request to array in order ot make password hash
        $data = $request->toArray();
//        if($data['password']){
//            $data['password'] = Hash::make($data['password']);
//        }
        ///Using ->update to update any field optionally and according to fillable
        /// in User model
        $user = User::findOrFail($id)->update($data);

        return response(['message'=>'User has been updated'],200);
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
}
