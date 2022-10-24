<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AuthUser;
use App\Models\ReferalTree;
use App\Models\Ternak;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function register(Request $request){
        $validation = [
            'username'=>'required|min:5|unique:users,username',
            'phone'=>'required|unique:users,phone',
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required'
        ];
        if(isset($request->email) || $request->email != null){
            $validation['email'] = 'required|email|unique:users,email';
        }
        if(isset($request->user_ref) || $request->user_ref != null){
            $validation['user_ref'] = 'required|min:5';
        }
        // return $request->all();
        $validator = Validator::make($request->all(),$validation);
        if ($validator->fails()) {
            return response()->json([
                "status" => 401,
                "message"=>"Validation Error!",
                'errors' => $validator->getMessageBag()],Response::HTTP_UNAUTHORIZED);
        }
        $cek_ref = User::where('user_ref',$request->user_ref)->first();
        if(!$cek_ref){
            $referal = null;
        }else{
            $referal = $cek_ref->id;
        }
        try{
            $user = User::create([
                'email'     => $request->email,
                'username'  => $request->username,
                'phone'     => $request->phone,
                'user_ref'  => User::makeReferal($request->username),
                'ref_to'    => $referal,
                'password'  => Hash::make($request->password)
            ]);
            if($referal != null){
                User::createLevelUser($user->id);
            }
            Ternak::giveFreeTernak($user->id);
            return response()->json([
                'status'    => "200",
                'message'   => 'User Sucessuly Registed',
                'data'      => User::find($user->id)
            ], Response::HTTP_OK);
        }catch (QueryException $e) {
            return response()->json([
                'status' => "401",
                'message' => 'Failed to create user',
                'data' => $e->errorInfo
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'message' => 'Error Validation',
                'errors' => $validator->getMessageBag()
            ],Response::HTTP_UNAUTHORIZED);
        }
        $user = User::where('username',$request->username)->first();
        if(!$user){
            $user = User::where('email',$request->username)->first();
        }
        $errors = [];
        // return response()->json(['data'=>$request->username]);
        if(!$user){
            return response()->json([
                'status'=>401,
                'message' => 'Error Validation',
                'errors'=> [
                    'username' => ['Username not found']
                ]],401);
        }
        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                'status' => 401,
                'message' => 'Error Validation',
                'errors'=> [
                    'password' => ['Wrong password']
                ]],401);
        }
        
        try {
            if(!$token = JWTAuth::attempt($request->all())){
                return response()->json([
                	'status' => "401",
                	'message' => 'Login credentials are invalid.',
                    'token' => '-'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            // return $credentials;
            return response()->json([
                	'status' => 500,
                	'message' => 'Could not create token: '.$e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Login Success',
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()], Response::HTTP_UNAUTHORIZED);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'status' => "200",
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => 500,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
