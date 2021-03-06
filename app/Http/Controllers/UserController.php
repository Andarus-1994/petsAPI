<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RegisterMail;

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
        date_default_timezone_set('Europe/Berlin');
        $date= date('Y-m-d H:i:s') ;
        $firebase = (new Factory)
        ->withServiceAccount(__DIR__.'/firebaseKey.json')
        ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
        ->createDatabase();
      
        $database = $firebase;

        $token = STR::random(60);
 
        $users = $database->getReference("Users")->getValue();
        if($users){
        foreach($users as $user){
           if($user['email'] ===$request->email)
           return response()->json(["error"=>"{\"email\":[\"Email already used.\"]}"]);
           if($user['user'] ===$request->user)
           return response()->json(["error"=>"{\"user\":[\"User already taken.\"]}"]);
           if($user['token']===$token)
            $token=$token+'17';
        }
        
    } 
 

       $user =  $database->getReference("Users")->push(["user"=>$request->json()->get('user'),
       'email' => $request->json()->get('email'),  'role'=>'casual',
            'password' => Hash::make($request->json()->get('password')),"time_stamp"=>$date, "token"=>$token, "verified"=>false] )->getValue();
            Mail::to($request->json()->get('email'))->send(new RegisterMail($token));
        return response()->json(compact('user'), 201);
    }

    public function validateUser(Request $request){
        $firebase = (new Factory)
        ->withServiceAccount(__DIR__.'/firebaseKey.json')
        ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
        ->createDatabase();
        $found = false;
        $database = $firebase;
        $users = $database->getReference("Users")->getValue();
        if($users){
            foreach($users as &$user){
             
                if($user['token']===$request->token){
                $user['token'] = 0;
                $user['verified']=true;
                  $found=true;  
                }
            
            } 
        }
        
        $database->getReference('Users')->set($users);
        if($found)
        return response()->json(["success"=>"Your account has been verified!"]);
        return response()->json(["error"=>"Your account has been verified already!"]);
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
                $payload = ['sub'=>'-McNng4hjjowUlpdUKUL','user'=>$user['user'], 'email'=>$user['email'], 'role'=>$user['role'], 'verified'=>$user['verified']];
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
          
          $userObject = ['user' => $user["user"],'email'=>$user['email'],'role'=>$user['role'], 'verified'=>$user['verified']];
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
