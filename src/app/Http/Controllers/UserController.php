<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{
    function login(Request $request){
        try {
            $user= User::where('email', $request->email)->first();
            // print_r($data);
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response([
                        'message' => ['These credentials do not match our records.']
                    ], 404);
                }
    
                $token = $user->createToken('my-app-token')->plainTextToken;
    
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
    
            return response($response, 201);
        }
        catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
       
    }

    function register(Request $request){
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->createBy = $request->createBy;
            $user->updateBy = $request->updateBy;
            $user->save();
    
            $response = [
                'user' => $user,
                'message'=>'User register successfully.'
            ];
       
            return  response()->json($response,200);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
        
    }

    function find(Request $request){
        try{
            $user = User::query()->orderBy('created_at', 'desc')->get();;
            $res = [
                'data' => $user,
                'message'=>"success",
            ];
       
            return response()->json($res,200);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
        
    }

    function update(Request $request){
        try{

            $user = User::find($request->id);
            $user->image = $request->image;
            $user->updateBy = $request->updateBy;
            $user->save();
       
            return response()->json(["data"=>$user, "message"=>'User update successfully.']);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
        
    }

    function delete(Request $request){
        try{
            User::where('id', $request->id)->delete();
            return response()->json(['message' => "User register successfully."], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => "{$e}"], 400);
        }
        
    }

    function logout(Request $request)
    {
        // remove all token about user
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
