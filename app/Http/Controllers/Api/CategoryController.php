<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Category;
use Validator;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCategory()
    {
        try{
            $products = Category::all();
            return $this->sendResponse($products->toArray(), 'Category retrieved successfully.');
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
    public function addCategory(Request $request)
    {
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required',
                'type' => 'required',
                'model' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(), 201);       
            }
            
            $category = new Category();
            $category->name	= $input['name'];
            $category->type	= $input['type'];
            $category->model	= $input['model'];
            $category->status	= 1;
            $category->save();
            $categoryId = $category->id;
            
            return $this->sendResponse(['id'=>$categoryId], 'Category created successfully.');
        }
        catch(\Exception $ex){
            return $this->sendError('Something went wrong.', ['error' => $ex->getMessage()], 201);  
        }
    }
}
