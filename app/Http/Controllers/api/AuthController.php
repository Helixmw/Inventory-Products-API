<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "Invalid Entry",
                "errors" => $validator->errors()], 400);
        }else{
            $user = User::create([
                'name'=>$request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'role'=> 0
            ]);
            $newuser =  (object) array("id" => $user->id,
            "name" => $user->name, 
            "email" => $user->email);          
            return response()->json(["success" => true,
                                    "message" => "Successfully added new user"
                                    "user" => $newuser ], 201);
        }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'=>'required|email',
                'password'=>'required'
            ]);
            if($validator->fails()){
                return response()->json(["Invalid Entry" => $validator->errors()], 400);
            }else{
                if(!Auth::attempt($request->only('email','password'))){
                    return response()->json([
                        "success" => false,
                        "message" => "Invalid Entry",
                        "errors" => $validator->errors()], 400);
                }else{
                    $user = User::where('email', $request->email)->first();
                    $token = $user->createToken(env("SECRET_KEY"));
                    return response()->json([
                    "success" => true,
                    "message" => "Logged in"
                    "user" => (object) array("id" => $user->id,
                                            "name" => $user->name, 
                                            "email" => $user->email),
                                            "access_token" => $token->plainTextToken,
                                            "token_type" => "Bearer"], 200);
                }
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }        
    }

    public function logout(Request $request){
        try{          
            $id = Auth::user()->currentAccessToken()->id;
            Auth::user()->tokens()->where('id', $id)->delete();
            return response()->json([
                "success" => true,
                "message" => "logged out"], 200);
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

   

    
}
