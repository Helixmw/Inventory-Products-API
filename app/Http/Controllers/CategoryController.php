<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        try{
            $result = Category::all()->sortDesc();
            $res = array();
            foreach($result as $row){
                $res[] = array("id" => $row->id, "name" => $row->name, "limit" => $row->quantity);
            } 
            return response()->json($res);
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
    }

    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), ['name'=>'required',
            'quantity'=>'required|integer']);
            if($validator->fails()){
                return response()->json(["invalid entry" => $validator->errors()], 400);
            }else{
                $result = Category::create($request->all());
                $res = (object) array('id'=> $result->id,"name" => $result->name, "limit" => $request->quantity);                    
                return response()->json($res, 201);
            }
        }catch(\Exception $e){
            return response()->json(["Error"=>"Server Error"], 500);
        }
}

public function getCategory(Request $request, $id){
    try{
        $result = Category::find($id);
        if($result == null){
            return response()->json(["Not Found"=>"No category found"], 404);
        }else{
            return response()->json((object) array("id" => $result->id,
                                                    "name" => $result->name,
                                                    "limit" => $result->quantity));
        }
    }catch(\Exception $e){
        return response()->json(["Error"=>"Server Error"], 500);
    }
}

public function editCategory(Request $request, $id){
    try{
       $result = Category::find($id);
       if($result == null){
        return response()->json(["Not Found"=>"No category found"], 404);
       }else{
        $validator = Validator::make($request->all(), ["name" => "required",
                                                        "quantity" => "required|integer"]);
        if($validator->fails()){
            return response()->json(["Invalid Entry" => $validator->errors()], 400);
        }else{
            $result->name = $request->name;
            $result->quantity = $request->quantity;
            $result->save();
            return response()->json((object) array("id" => $result->id,
                                                    "name" => $result->name,
                                                    "limit"=>$result->quantity), 201);
        }
       }
    }catch(\Exception $e){
        return response()->json(["Error"=>"Server Error " . $e->getMessage()], 500);
    }
}

public function delete(Request $request, $id){
    try{
        $result = Category::find($id);
        if($result == null){
         return response()->json(["Not Found"=>"No category found"], 404);
        }else{
            $result->delete();
            return response()->json(["deleted" => "One category has been deleted"]);
        }
    }catch(\Exception $e){
        return response()->json(["Error"=>"Server Error "], 500);
    }
}


}
