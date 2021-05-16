<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{
    public function addPost(Request $request)
    {
       $searchedPost = [];
       
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
      
     $newPost = $database->getReference($request->location)->push(["charName"=>$request->name,"message"=>$request->message , "id_reply"=>$request->id_reply]);
      
   //    $newPost = $database->getReference('shadowlands/familiarExorcist/sylla/posts')->getValue();
   if($newPost->getValue())
   return response()->json(['success' => 'Your message has been posted']);
       return $newPost->getValue(); 
       
    /*
       $newPost2 = $database->getReference('blog/posts')->getChildKeys();
     array_push($searchedPost,$database->getReference('blog/posts')->orderByKey()->equalTo("-M_ixvXhmAwph6Cj5iXX")->getValue());
      
     
        return $searchedPost;
        */
    }

    public function getPosts(Request $request)
    {
       $searchedPost = [];
      
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
     
     $newPost = $database->getReference($request->location)->getValue();
    
     if($newPost)
      return $newPost;
      return response()->json(['Empty' => 'No messages yet']);
   //    $newPost = $database->getReference('shadowlands/familiarExorcist/sylla/posts')->getValue();
  
       
    /*
       $newPost2 = $database->getReference('blog/posts')->getChildKeys();
     array_push($searchedPost,$database->getReference('blog/posts')->orderByKey()->equalTo("-M_ixvXhmAwph6Cj5iXX")->getValue());
      
     
        return $searchedPost;
        */
    }


 

}