<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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
            ]);
            $token = $user->createToken('InventoryApp');
            $newuser =  (object) array("id" => $user->id,
            "name" => $user->name, 
            "email" => $user->email);
            return response()->json(["success" => $newuser,"token" => $token->plainTextToken], 201);
        }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
    }
}
