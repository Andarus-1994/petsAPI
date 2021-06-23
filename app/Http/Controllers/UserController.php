<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;



class UserController extends Controller
{
    //

    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toJson()]);
        }
        
        $firebase = (new Factory)
        ->withServiceAccount(__DIR__.'/firebaseKey.json')
        ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
        ->createDatabase();
      
        $database = $firebase;

        $users = $database->getReference("Users")->getValue();
        if($users){
        foreach($users as $user){
           if($user['email'] ===$request->email)
           return response()->json(["error"=>"Email already used."]);
        }
    }

       $user =  $database->getReference("Users")->push(["user"=>$request->json()->get('user'),
       'email' => $request->json()->get('email'),  'role'=>'casual',
            'password' => Hash::make($request->json()->get('password')),])->getValue();

        return response()->json(compact('user'), 201);
    }

    public function login(Request $request)
    {
       
        
        $firebase = (new Factory)
        ->withServiceAccount(__DIR__.'/firebaseKey.json')
        ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
        ->createDatabase();
      
        $database = $firebase;

        $users = $database->getReference("Users")->getValue();

      

        $header = $request->header('Authorization');

       if($users){
        foreach($users as $user){
           if($user['email'] ===$request->email  &&  !$header ){
            if (Hash::check($request->password, $user['password'])) {
                $headers = ['alg'=>'HS256','typ'=>'JWT'];
                $headers_encoded = $this->base64url_encode(json_encode($headers));
                
                //build the payload
                $payload = ['sub'=>'-McNng4hjjowUlpdUKUL','user'=>$user['user'], 'email'=>$user['email'], 'role'=>$user['role']];
                $payload_encoded = $this->base64url_encode(json_encode($payload));
                
                $key = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
                $signature = hash_hmac('sha256',"$headers_encoded.$payload_encoded",$key,true);
                $signature_encoded = $this->base64url_encode($signature);
                
                //build and return the token
                $token = "$headers_encoded.$payload_encoded.$signature_encoded";
               
                $decrypted =json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
                return response()->json(["user"=>$decrypted,"token"=>$token],200);
            }
            return response()->json(["error"=>"Wrong Password."]);
           }
          
           if($header){
            $decrypted =json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));
            if( $user['email']===$decrypted->email)
           {
          
          $userObject = ['user' => $user["user"],'email'=>$user['email'],'role'=>$user['role']];
            return response()->json(["Success"=>$userObject]);
           }
           }
        }
    }

    return response()->json(["error"=>"There is no user with this email!"]);
    }

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
