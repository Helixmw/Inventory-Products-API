<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Interfaces\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
class ServicesController extends Controller implements ProductInterface
{
    public function index(){
        try{
            $result = Product::all()->sortDesc();
            if($result->count() == 0){
                return response()->json([
                    "success" => false,
                    "message" => "There are no services"], 404); 
            }else{
                $res = array();
            foreach($result as $row){
                $res[] = array("id" => $row->id,
                            "name" => $row->name,
                            "category" => $row->name,
                            "quantity" => $row->quantity);
            } 
            return response()->json(["success" => true, "services" => $res], 200);
            }          
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), ['name'=>'required',
                                                        'description'=>'required',
                                                        'price' => 'required|integer'],
                                                    ['name.required' => 'Please add the service name',
                                                    'description.required' => 'Please provide a description about your service',
                                                    'price.required' => 'Please add the service price']);
            if($validator->fails()){
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Entry",
                    "errors" => $validator->errors()], 400);
            }else{
                $result = Product::create($request->all());
                $res = (object) array("id"=> $result->id,
                                        "name" => $result->name, 
                                        "description" => $request->description,                                
                                        "price" => $request->price);                    
                return response()->json(["success" => true, "services" => $res], 201);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
    public function getProduct(Request $request, $id){
        try{
            $result = Product::find($id);
            if($result == null){
                return response()->json(["Not Found"=>"No service found"], 404);
            }else{
                return response()->json([
                    "success" => true,    
                    "service" => (object) array("id" => $result->id,
                                                        "name" => $result->name,
                                                        "description" => $result->description,                                                      
                                                        "price" => $result->price)]);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
    public function editProduct(Request $request, $id){
        try{
            $result = Product::find($id);
            if($result == null){
             return response()->json([
                 "success" => true,
                 "message"=>"No product found"], 404);
            }else{
                $validator = Validator::make($request->all(), ['name'=>'required',
                                                                'description'=>'required',
                                                                'price' => 'required|integer'],
                                                                ['name.required' => 'Please add the service name',
                                                                'description.required' => 'Please provide a description about your service',
                                                                'price.required' => 'Please add the service price']);
             if($validator->fails()){
                 return response()->json([
                     "success" => false,
                     "message" => "Invalid Entry",
                     "errors" => $validator->errors()], 400);
             }else{
                 $result->name = $request->name;
                 $result->quantity = $request->quantity;
                 $result->categoryId = $request->categoryId;
                 $result->price = $request->price;
                 $result->save();
                 return response()->json([
                                     "success" => true,    
                                     "service" => (object) array("id" => $result->id,
                                                         "name" => $result->name,
                                                         "description" => $result->description,
                                                         "price"=> $result->price)], 201);
             }
            }
         }catch(\Exception $e){
             return response()->json([
                 "success" => false,
                 "message" => "Server Error " . $e->getMessage()], 500);
         }
    }
    public function delete(Request $request, $id){
        try{
            $result = Product::find($id);
            if($result == null){
             return response()->json(["success" => false,"message"=>"No service found"], 404);
            }else{
                $result->delete();
                return response()->json(["success" => true, "message" => "One service has been deleted"]);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }
}
