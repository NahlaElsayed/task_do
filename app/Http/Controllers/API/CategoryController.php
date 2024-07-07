<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function add(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
      
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $category = new Category();
        $category->title = $request->input('title');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');
        $category->save();
       

        return $this->sendResponse($category, 'Add category Successfully');

    }


    public function all()
    {
     
       $category = Category::with('parent')->get();

        return $this->sendResponse($category, 'All category Successfully');
    }



    public function updateCategory(Request $request,$id){
        $validator =Validator::make($request->all(), [
            'title' => 'nullable',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
      
      
        $category= Category::find($id);

    if($category){

                   
                $category->update($request->all());
                $category->save();
            
            return $this->sendResponse($category, 'update category successfully.');
        
    }
    else{
        return $this->sendError( 'category Not Found');
    }
      
    }


    public function destroy($id){

        $category = Category::find($id);
        if($category){
            $category->delete();
        return $this->sendResponse($category, 'delete category Successfully');

        }
        else{
            return $this->sendError( 'category Not Found');
        }
}
}
