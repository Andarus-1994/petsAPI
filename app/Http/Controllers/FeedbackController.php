<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class FeedbackController extends Controller
{
    //
    public function addFeedback(Request $request)
    {
     //  $TimeNow = date('Y-m-d H:i:s');
     date_default_timezone_set('Europe/Berlin');
     $date= date('Y-m-d H:i:s') ;
 
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
      
     $feedback = $database->getReference('feedback')->push(["user"=>$request->user,"title"=>$request->title , "message"=>$request->message,"time_stamp"=>$date]);
      
   //    $feedback = $database->getReference('shadowlands/familiarExorcist/sylla/posts')->getValue();
   if($feedback->getValue())
   return response()->json(['success' => 'Your message has been posted']);
       return $feedback->getValue(); 
       
    }

    public function getFeedback()
    {
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
     
     $feedback = $database->getReference('feedback')->getValue();
     
     if($feedback)
      return $feedback;
      return response()->json(['Empty' => 'No Feedback Yet.']);
    }
    
}
