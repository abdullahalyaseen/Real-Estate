<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function getAllUsers()
    {
        return User::with('role')->get();
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
        $user = User::findOrFail($id);

        if ($request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
            $user->last_name = $request->last_name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->role) {
            $user->role = $request->role;
        }
        if ($request->number) {
            $user->number = $request->number;
        }
        $user->save();
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
