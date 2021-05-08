<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Product;
use App\Category;
use Validator;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listProducts()
    {
        try{
            $products = Product::all();
            return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
        }
        catch(\Exception $ex){
            return $this->sendError('Something went wrong.', ['error' => $ex->getMessage()], 201); 
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'category' => 'required',
                'description' => 'required',
                'price' => 'required',
                'make' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            
            $categoryData = Category::where('type',$input['category'])->first();
            if(empty($categoryData)){
                throw new \Exception("Category Not found");
            }
            
            $product = new Product();
            $product->name          = $input['name'];
            $product->category      = $categoryData->id;
            $product->description   = $input['description'];
            $product->price         = $input['price'];
            $product->make          = $input['make'];
            $product->status        = 1;
            $product->save();
            $productData = $product->id;
            
            return $this->sendResponse(['id'=>$productData], 'Product created successfully.');
        }
        catch(\Exception $ex){
            return $this->sendError('Something went wrong.', ['error' => $ex->getMessage()], 201);  
        }
    }
    
}
