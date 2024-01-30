<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        try{
            $brand = Brand::all()->sortDesc();
            $res = array();
            foreach($brand as $row){
                $res[] = array("id" => $row->id,
                "name" => $row->name,
                "description" => $row->description);
            }
            return response()->json([
                "success" => true,
                "brands" => $res], 200);
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if($validator->fails()){
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Entry",
                    "errors" => $validator->errors()], 400);
            }else{
                $brand = Brand::create($request->all());
                return response()->json([
                    "success" => true,
                    "message" => "Successfully added new brand"
                    "brand" => (object) array("id" => $brand->id, "name" => $brand->name)], 201);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function getBrand(Request $request, $id){
        try{
            $brand = Brand::find($id);
            if($brand == null){
                return response()->json([
                    "success" => false,
                    "message"=>"Could not find brand"], 404);
            }else{
                return response()->json([
                    "success" => true,
                    "brand" => (object) array("id" => $brand->id, "name" => $brand->name)]);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }

    public function editBrand(Request $request, $id){
        try{
            $brand = Brand::find($id);
            if($brand == null){
                return response()->json([
                    "success" => false,
                    "message"=>"Could not find brand"], 404);
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "success" => false,
                        "message" => "Invalid Entry",
                        "errors" => $validator->errors()], 400);
                }else{
                $brand->name = $request->name;
                $brand->description = $request->description;
                $brand->save();
                return response()->json((object) array("id" => $brand->id, "name" => $brand->name));
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
            $brand = Brand::find($id);
            if($brand == null){
                return response()->json([
                    "sucess" => false,
                    "message"=>"Could not find brand"], 404);
            }else{
                $brand->delete();
                return response()->json([
                    "success" => true,
                    "message"=>"One brand has been deleted"]);
            }
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "Server Error " . $e->getMessage()], 500);
        }
    }


}
