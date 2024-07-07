<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function add(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
      
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename ='t'. time() . '.' . $image->getClientOriginalExtension();
            $path = $image->move('images', $filename);
        }

        $product = new Product();
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->image = $path;
        $product->save();
       

        return $this->sendResponse($product, 'Add product Successfully');

    }


    public function all()
    {
     
       $product = Product::with('category')->get();

        return $this->sendResponse($product, 'All product Successfully');
    }


    public function updateProduct(Request $request,$id){
        $validator =Validator::make($request->all(), [
            'title' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
      
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->move('images', $filename);
        }

        $product= Product::find($id);

    if($product){

                    if($request->image){
                        $product->image = $path;
                        $product->save();
                    }
              
                $product->update($request->all());
                $product->save();
            
            return $this->sendResponse($product, 'update Product successfully.');
        
    }
    else{
        return $this->sendError( 'Product Not Found');
    }
      
    }

    public function destroy($id){

        $product = Product::find($id);
        if($product){
            $product->delete();
        return $this->sendResponse($product, 'delete product Successfully');

        }
        else{
            return $this->sendError( 'Product Not Found');
        }
        

    }
}
