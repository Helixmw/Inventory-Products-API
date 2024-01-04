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
            return response()->json(["Invalid Entry" => $validator->errors()], 400);
        }else{
            $user = User::create([
                'name'=>$request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
                'role'=> 0
            ]);
            $token = $user->createToken('InventoryApp');
            $newuser =  (object) array("id" => $user->id,
            "name" => $user->name, 
            "email" => $user->email);
            return response()->json(["success" => $newuser,
                                    "access_token" => $token->plainTextToken,
                                    "token_type" => "Bearer"], 201);
        }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error " . $e->getMessage()], 500);
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
                    return response()->json(['Invalid Credentials'=> 'Email and Password do not match.'], 400);
                }else{
                    $user = User::where('email', $request->email)->first();
                    $token = $user->createToken("InventoryApp");
                    return response()->json(["success" => (object) array("id" => $user->id,
                     "name" => $user->name, 
                     "email" => $user->email),
                    "access_token" => $token->plainTextToken,
                    "token_type" => "Bearer"], 200);
                }
            }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }        
    }

    public function logout(Request $request){
        try{          
            $id = Auth::user()->currentAccessToken()->id;
            Auth::user()->tokens()->where('id', $id)->delete();
            return response()->json(["success"=> "logged out"], 200);
        }catch(\Exception $e){
            return response()->json(["Error"=> "Server Error "], 500);
        }
    }

   

    
}
