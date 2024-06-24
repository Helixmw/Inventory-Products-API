<?php
namespace App\Http\Classes;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class Products{

    public function __construct(){

    }

    public function GetAll(){
        $result = Product::all()->sortDesc();
        foreach($result as $row){
            $res[] = array("id" => $row->id,
                        "name" => $row->name,
                        "category" => $row->name,
                        "quantity" => $row->quantity);
        } 
        return response()->json(["success" => true, "products" => $res], 200);
    }

    public function AddProduct($request){
        $result = Product::create($request->all());
                $res = (object) array("id"=> $result->id,
                                        "name" => $result->name, 
                                        "category" => $request->categoryId,
                                        "quantity" => $request->quantity,
                                        "price" => $request->price);                    
                return response()->json(["success" => true, "product" => $res], 201);
    }

    public function GetProductById($id){
        $result = Product::find($id);
        return response()->json([
            "success" => true,    
            "product" => (object) array("id" => $result->id,
                                                "name" => $result->name,
                                                "category" => $result->categoryId,
                                                "quantity" => $result->quantity,
                                                "price" => $result->price)]);
    }

    public function ProductValidation($request){
        return Validator::make($request->all(), ['name'=>'required',
                                                        'quantity'=>'required|integer',
                                                        'categoryId' => 'required|integer',
                                                        'price' => 'required']);
    }

    public function ErrorMessage($e){
        return response()->json([
            "success" => false,
            "message" => "Server Error " . $e->getMessage()], 500);
    }
}