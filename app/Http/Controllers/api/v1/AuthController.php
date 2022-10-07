<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'username'=>'required|min:5|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()],Response::HTTP_UNAUTHORIZED);
        }
       try {
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' =>  Hash::make($request->password)
            ]);
            return response()->json([
                'status' => "200",
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
       } catch (QueryException $e) {
            return response()->json([
                'status' => "401",
                'message' => 'Failed to create user',
                'data' => $e->errorInfo
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
       }
    }
    public function authenticate(Request $request){
        $credentials = $request->only('username', 'password');
        $validate = Validator::make($credentials,[
            'username' => 'required',
            'password' => 'required'
        ]);
        // return response()->json($request->all());
        if ($validate->fails()) {
            return response()->json(['error' => $validate->getMessageBag()], Response::HTTP_UNAUTHORIZED);
        }
        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                	'status' => "401",
                	'message' => 'Login credentials are invalid.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                	'status' => 500,
                	'message' => 'Could not create token.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Login Success',
            'token' => $token,
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
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }
}
