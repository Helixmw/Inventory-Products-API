<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Http\Classes\Products;

use App\Http\Interfaces\ProductInterface;


class ProductsController extends Controller implements ProductInterface
{

    private $products;

    public function __construct(){
        $this->products = new Products();
    }

    public function index(){
        try{
            $this->products->GetAll();  
        }catch(\Exception $e){
            $this->products->ErrorMessage($e); 
        }
    }

    public function add(Request $request){
        try{
            $validator = $this->products->ProductValidation($request);
            if($validator->fails()){
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Entry",
                    "errors" => $validator->errors()], 400);
            }else{
                $this->products->AddProduct($request);
            }
        }catch(\Exception $e){
            $this->products->ErrorMessage($e); 
        }
    }

public function getProduct(Request $request, $id){
    try{
        $this->products->GetProductById($id);
    }catch(\Exception $e){
        $this->products->ErrorMessage($e); 
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
        $validator = Validator::make($request->all(), ["name" => "required",
                                                        "quantity" => "required|integer",
                                                        'categoryId' => 'required|integer',
                                                        'price' => 'required'
                                                    ]);
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
                                "product" => (object) array("id" => $result->id,
                                                    "name" => $result->name,
                                                    "quantity"=> $result->quantity,
                                                    "category"=> $result->categoryId,
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
         return response()->json(["success" => false,"message"=>"No product found"], 404);
        }else{
            $result->delete();
            return response()->json(["success" => true, "message" => "One product has been deleted"]);
        }
    }catch(\Exception $e){
        return response()->json([
            "success" => false,
            "message" => "Server Error " . $e->getMessage()], 500);
    }
}

public function GetCategoryProducts(Request $request, $categoryId){
    try{
        $result = Product::where('categoryId', $categoryId)->get()->sortDesc();
        if($result->count() == 0){
           return response()->json([
                                "success" => false,
                                "massge" => "There are no products under this category"], 404); 
        }else{         
            $res = array();
            foreach($result as $row){
                $res[] = array("id" => $row->id,
                "name" => $row->name,
                "category" => $row->categoryId,
                "quantity" => $row->quantity);
            }
            return response()->json(["success" => true, "products" => $res]);
        }
    }catch(\Exception $e){
        return response()->json([
            "success" => false,
            "message" => "Server Error " . $e->getMessage()], 500);
    }
}

public function GetBrandProducts(Request $request, $brandId){
    try{
        $result = Product::where('brandId', $brandId)->get()->sortDesc();
        if($result->count() == 0){
           return response()->json([
            "success" => false,
            "message" => "There are no products for this brand"], 404); 
        }else{         
            $res = array();
            foreach($result as $row){
                $res[] = array("id" => $row->id,
                "name" => $row->name,
                "category" => $row->categoryId,
                "quantity" => $row->quantity);
            }
            return response()->json(["success" => true, "products" => $res]);
        }
    }catch(\Exception $e){
        return response()->json([
            "success" => false,
            "message" => "Server Error " . $e->getMessage()], 500);
    }
}

public function GetCategoryAndBrandProducts(Request $request, $categoryId, $brandId){
    try{
        $result = Product::where('categoryId', $categoryId)->where('brandId', $brandId)->get()->sortDesc();
        if($result->count() == 0){
           return response()->json([
                            "success" => true,
                            "message" => "There are no products under this brand and category"], 404); 
        }else{         
            $res = array();
            foreach($result as $row){
                $res[] = array("id" => $row->id,
                "name" => $row->name,
                "category" => $row->categoryId,
                "quantity" => $row->quantity);
            }
            return response()->json(["success" => true, "products" => $res]);
        }
    }catch(\Exception $e){
        return response()->json([
            "success" => false,
            "message" => "Server Error " . $e->getMessage()], 500);
    }
}

}
