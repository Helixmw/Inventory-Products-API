<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UsersController extends Controller
{

    public function GetAllUsers(Request $request){
        try{
            $users = User::all()->sortDesc();
            if($users->count() == 0){
                return response()->json([
                                    "success" => false,
                                    "message" => "No users found"], 404);
            }else{   
                $results = array();
                foreach($users as $row){
                    $results[] = array("id"=> $row->id, "name"=> $row->name,"email"=> $row->email);
                }
                return response()->json(["success" => true,"users" => $results], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
    public function EditUser(Request $request, $id){
        try{
        $validator = Validator::make($request->all(), [
                                        'email'=>'required|email',
                                        'name' => 'required'
                                ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "Invalid Entry",
                "errors" => $validator->errors()], 400);
        }else{     
            $user = User::find($id);
            if($user == null){
                return response()->json(["success" => false, "message"=> "User not found"], 404);
            }else{
                $user->email = $request->email;
                $user->name = $request->name;
                $user->save();
                return response()->json([
                                        "success" => true,
                                        "user" => (object) array("id" => $user->id,
                                                                "name" => $user->name,
                                                                "email"=> $user->email)], 200);
            }
        }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function GetUser(Request $request, $id){
        try{            
            $user = User::find($id);
            if($user == null){
                return response()->json([
                                "success" => false,
                                "message" => "User not found"], 404);
            }else{            
                return response()->json(["success" => true, 
                                        "user" => (object) array("id" => $user->id,
                                                                "name" => $user->name,
                                                                "email" => $user->email)], 200);
            }    
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function DeleteUser(Request $request, $id){
        try{            
            $user = User::find($id);
            if($user == null){
                return response()->json(["success" => false, "message" => "User not found"], 404);
            }else{   
                $user->delete();        
                return response()->json(["success" => true, "message"=> "One user has been deleted"], 200);
            }    
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function AssignAdministrator(Request $request, $id){
        try{
            $user = User::where('id', $id)->first();
            if($user == null){
                return response()->json([
                                "success" => true,
                                "message" => "Could not find user"], 404);
            }else{
                if($user->role == 0 || $user->role == null){
                    $user->role = 1;
                }else{
                    $user->role = 0;
                }
                $user->save();
                if($user->role == 0){
                    return response()->json([
                                    "success" => true,
                                    "message" => "User is no longer administrator"], 200);
                }else{
                    return response()->json(["success" => true,
                                    "message" => "User is now a administrator"], 200);
                }
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
}
