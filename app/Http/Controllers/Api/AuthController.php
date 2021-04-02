<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function login(Request $request){

           $validated = $request->validate([
               'email' => 'required|email',
               'password' => 'required',
           ]);

           if(!Auth::attempt($validated)){
               return response()->json(['message' =>'not allowed'],401);
           }
           $user = User::where('email',$validated['email'])->first();
           if(Hash::check($validated['password'],$user->password)){
               auth()->user()->tokens()->delete();
               $token = auth()->user()->createToken('API Token')->plainTextToken;
               return response()->json(['token'=>$token],200);
           }
   }
}
