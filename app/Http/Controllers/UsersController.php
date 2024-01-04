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
                return response()->json(["Not Found" => "No users found."], 404);
            }else{   
                $results = array();
                foreach($users as $row){
                    $results[] = array("id"=> $row->id, "name"=> $row->name,"email"=> $row->email);
                }
                return response()->json(["success" => $results]);
            }
        }catch(\Exception $e){
            return response()->json(["Error" => "Server Error"], 500);
        }
    }
    public function EditUser(Request $request, $id){
        try{
        $validator = Validator::make($request->all(), [
                                        'email'=>'required|email',
                                        'name' => 'required'
                                ]);
        if($validator->fails()){
            return response()->json(["Invalid Entry" => $validator->errors()], 404);
        }else{     
            $user = User::find($id);
            if($user == null){
                return response()->json(["Not Found"=> "User not found."], 404);
            }else{
                $user->email = $request->email;
                $user->name = $request->name;
                $user->save();
                return response()->json(["success"=> (object) array("id" => $user->id,
                                        "name" => $user->name,
                                        "email"=> $user->email)]);
            }
        }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
    }

    public function GetUser(Request $request, $id){
        try{            
            $user = User::find($id);
            if($user == null){
                return response()->json(["Not Found" => "User not found."], 404);
            }else{            
                return response()->json(["success"=> (object) array("id" => $user->id,
                                        "name" => $user->name,
                                        "email"=> $user->email)]);
            }    
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
    }

    public function DeleteUser(Request $request, $id){
        try{            
            $user = User::find($id);
            if($user == null){
                return response()->json(["Not Found" => "User not found."], 404);
            }else{   
                $user->delete();        
                return response()->json(["success"=> "One user has been deleted."]);
            }    
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
    }

    public function AssignAdministrator(Request $request, $id){
        try{
            $user = User::where('id', $id)->first();
            if($user == null){
                return response()->json(["Not Found" => "Could not find user."], 404);
            }else{
                if($user->role == 0 || $user->role == null){
                    $user->role = 1;
                }else{
                    $user->role = 0;
                }
                $user->save();
                if($user->role == 0){
                    return response()->json(["revoked" => "User is no longer administrator"]);
                }else{
                    return response()->json(["added" => "User is now a administrator"]);
                }
            }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error "], 500);
        }
    }
}
