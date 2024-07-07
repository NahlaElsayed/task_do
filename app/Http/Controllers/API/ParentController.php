<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;


use App\Models\Parennt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParentController extends BaseController
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


        $parent = new Parennt();
        $parent->title = $request->input('title');
        $parent->description = $request->input('description');
        $parent->save();
       

        return $this->sendResponse($parent, 'Add Parent Successfully');

    }


    public function all()
    {
     
       $parent = Parennt::all();

        return $this->sendResponse($parent, 'All Parent Successfully');
    }

}
