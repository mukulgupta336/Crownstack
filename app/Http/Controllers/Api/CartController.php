<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Cart;
use App\Product;
use App\Category;
use Validator;

class CartController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buyProduct(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'product_id' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(), 201);       
            }
            
            if(empty($request->user()->id)){
                throw new \Exception("Please login again");
            }
            
            $productData = Product::where('id',$input['product_id'])->first();
            if(empty($productData)){
                throw new \Exception("Sorry !!! Product Not found");
            }
            
            
            $cart = new Cart();
            $cart->product_id   = $input['product_id'];
            $cart->user_id      = $request->user()->id;
            $cart->status	= 1;
            $cart->save();
            $cartData = $cart->id;
            
            return $this->sendResponse(['id'=>$cartData], 'Product Added to Cart successfully.');
        }
        catch(\Exception $ex){
            return $this->sendError('Something went wrong.', ['error' => $ex->getMessage()], 201);  
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myCart(Request $request)
    {
        if(empty($request->user()->id)){
            throw new \Exception("Please login again");
        }
        $productList = [];
        $userId = $request->user()->id;
            
        $myProducts = Cart::where('user_id',$userId)->where('status',1)->get()->toArray();
        
        if(!empty($myProducts)){
            foreach($myProducts as $key => $myProduct){
                $row = [];
                $productData = Product::where('id',$myProduct['product_id'])->first();
                $myProducts[$key]['product_name'] = $productData->name;
                $row['user_id'] = $myProduct['user_id'];
                $row['product_id'] = $myProduct['product_id'];
                $row['product_name'] = $productData->name;
                $row['created_at'] = $myProduct['created_at'];
                $productList[] = $row;
            }
        }
        if (empty($productList)) {
            return $this->sendError('No product added to cart.');
        }
        return $this->sendResponse($productList, 'Products retrieved successfully.');
    }
}
