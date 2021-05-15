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
      
     $newPost = $database->getReference($request->location)->push(["charName"=>$request->name,"message"=>$request->message]);
      
   //    $newPost = $database->getReference('shadowlands/familiarExorcist/sylla/posts')->getValue();
       return $newPost->getValue(); 
       
    /*
       $newPost2 = $database->getReference('blog/posts')->getChildKeys();
     array_push($searchedPost,$database->getReference('blog/posts')->orderByKey()->equalTo("-M_ixvXhmAwph6Cj5iXX")->getValue());
      
     
        return $searchedPost;
        */
    }

 

}