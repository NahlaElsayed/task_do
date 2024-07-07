<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\User;
use App\Mail\UserMail;
use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRegiserController extends BaseController
{
    public function register(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
      
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $user = new User();
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->save();
       

        return $this->sendResponse($user, 'Register Successfully');

    }



    public function login(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::where('email' , $request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
  
                $user->generateOtpCode();
            
                $details = [
                    'title' => 'Mail From Swap.com',
                    'body' => 'Welcome To  Swap ! You will receive an email with details when your Swap is approved.',
                    'code' => 'Swap OTP Code is: ' . $user->code
                ];

                Mail::to($user->email)->send(new UserMail( $details));
                $user->save();
          
                return $this->sendResponse($user, 'OTP sent successfully.');
            }
        
            else{
                return $this->sendError('Validation Error.', "password not correct ");

            }
        }
        else{
        
            return $this->sendError('Validation Error.', "User Not found");
        }

        

    }


    public function verifyOTP(Request $request )
    {
        $validator =Validator::make($request->all(), [
            'email' => 'nullable',
            'otp' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::where('email', $request->email)->first();

        $enteredOTP = (int) $request->input('otp');
        $correctOTP = (int) $user->code; // correct OTP value
        if ($enteredOTP === $correctOTP) {
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['fname'] =  $user->fname;
            $success['lname'] =  $user->lname;
            $success['email'] =  $user->email;
            $success['phone'] =  $user->phone;

            return $this->sendResponse($success, 'User verfiy successfully.');
        } else {
            return $this->sendError( 'Invalid OTP.');
        }
    }

    // public function forgetEmail(Request $request)
    // {
    //     $validator =Validator::make($request->all(), [
    //         'email' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }

    //     $user = User::where('email', $request->phone)->first();
    //     if($user){
    //         $user->generateOtpCode();
            
    //         $details = [
    //             'title' => 'Mail From Swap.com',
    //             'body' => 'Welcome To  Swap ! You will receive an email with details when your Swap is approved.',
    //             'code' => 'Swap OTP Code is: ' . $user->code
    //         ];

    //         Mail::to($user->email)->send(new UserMail( $details));
    //         return $this->sendResponse($user, 'OTP sent successfully.');
    //     }else{
        
    //         return $this->sendError('Validation Error.', "User Not found");
    //     }
    // }



    // public function changeForgetPassword(Request $request ){

    //     $validator =Validator::make($request->all(), [
    //         'password' => 'required',
    //         'confrim_password' => 'required|same:password',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors());
    //     }
       
    //     $user_id = auth()->id();
    //     // $client = Client::where('user_id',$user_id)->first();
    //     $user = User::where('id',$user_id)->first();
        
    //         if ($user) {
              

    //             $user->password = Hash::make($request->password);
    //             $user->save();
                
    //         return $this->sendResponse($user, ' change password successfully.');
    //     }
    //     else{
    //         return $this->sendError( 'User Not Found');
    //     }

    // }


    public function updatePassword(Request $request ){
        $validator =Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required',
            'confrim_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        

        $user_id = auth()->id();
        // $client = Client::where('user_id',$user_id)->first();
        $user = User::where('id',$user_id)->first();

          
            if(Hash::check($request->current_password, $user->password)){

               

                $user->password = Hash::make($request->password);
                $user->save();
            return $this->sendResponse($user, ' change password successfully.');
        }
        else{
            return $this->sendError( 'old password does not match ..');
        }
    }
    public function updateProfile(Request $request){
        $validator =Validator::make($request->all(), [
            'fname' => 'nullable',
            'lname' => 'nullable',
            'email' => 'nullable',
            'phone' => 'nullable',
     
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        $user_id = auth()->id();
        $user = User::where('id',$user_id)->first();

            if ($user) {
       
                $user->update($request->all());
                $user->save();

            return $this->sendResponse($user, 'update profile user successfully.');
        }
        else{
            return $this->sendError( 'User Not Found');
        }

    }



    public function getUserDate(){

        $user_id = auth()->id();
        $user = User::where('id',$user_id)->first();

        if ($user) {

            $date= [
                'name'=>$user->fname ." ".$user->lname,
                'email'=>$user->email,
                'phone' => $user->phone
            ];
            return $this->sendResponse($date, ' get date user successfully.');
        }
        else{
            return $this->sendError( 'User Not Found');
        }

    }

//     public function logout(Request $request)
// {
//     $user = $request->user();
//     $user->tokens()->delete();

//         return $this->sendResponse($user, ' logout successfully.');
    
// }

}
