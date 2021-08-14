<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class StrategyController extends Controller
{
    //



    public function addStrategy(Request $request)
    {
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
      
      
        //   return $request->instructions;
        $newPost = $database->getReference("Strategy/".$request->location)->push(["pet1_id"=>$request->pet1["id"],
     "pet1_ability_1"=>$request->pet1["abilitiesChosen"][1],"pet1_ability_2"=>$request->pet1["abilitiesChosen"][2],"pet1_ability_3"=>$request->pet1["abilitiesChosen"][3],
     "pet1_breed"=>$request->pet1['breed'],"pet1_rarity"=>$request->pet1['rarity'],"pet1_level"=>$request->pet1['level'],
     "pet2_id"=>$request->pet2["id"],
     "pet2_ability_1"=>$request->pet2["abilitiesChosen"][1],"pet2_ability_2"=>$request->pet2["abilitiesChosen"][2],"pet2_ability_3"=>$request->pet2["abilitiesChosen"][3],
     "pet2_breed"=>$request->pet2['breed'],"pet2_rarity"=>$request->pet2['rarity'],"pet2_level"=>$request->pet2['level'],
     "pet3_id"=>$request->pet3["id"],
     "pet3_ability_1"=>$request->pet3["abilitiesChosen"][1],"pet3_ability_2"=>$request->pet3["abilitiesChosen"][2],"pet3_ability_3"=>$request->pet3["abilitiesChosen"][3],
     "pet3_breed"=>$request->pet3['breed'],"pet3_rarity"=>$request->pet3['rarity'],"pet3_level"=>$request->pet3['level'],"validation"=>false,"title"=>$request->title,"optionalTips"=>$request->optionalTips,"author"=>$request->author, "rng"=>$request->rng]);
      
        foreach ($request->instructions as $instruction) {
            $database->getReference("StrategyInstructions/".$request->location)->push(["id_strategy"=>$newPost->getKey(),"turn"=>$instruction["turn"],"instruction"=>$instruction["instructions"]]);
        }

        if ($newPost->getValue()) {
            return response()->json(['success' => 'Your strategy has been sent for verification! Thank you for your devotion!']);
        }
        //   return $newPost->getKey();
       
    /*
       $newPost2 = $database->getReference('blog/posts')->getChildKeys();
     array_push($searchedPost,$database->getReference('blog/posts')->orderByKey()->equalTo("-M_ixvXhmAwph6Cj5iXX")->getValue());


        return $searchedPost;
        */
    }

    public function getStrategies(Request $request)
    {
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
    
        $strategies = $database->getReference("Strategy/".$request->location)->getValue();
  
  
     
        if ($strategies) {
            return $strategies;
        }
        return response()->json(['Empty' => 'No Strategies Available Yet.']);
    }

    public function getInstructions(Request $request)
    {
        $firebase = (new Factory)
                    ->withServiceAccount(__DIR__.'/firebaseKey.json')
                    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
                    ->createDatabase();
                  
        $database = $firebase;
    
        $strategies = $database->getReference("StrategyInstructions/".$request->location)->getValue();
        $finalInstructions = [];
        foreach ($strategies as $instruction) {
            if ($instruction['id_strategy']===$request->id_strategy) {
                array_push($finalInstructions, $instruction);
            }
        }
     
        if ($finalInstructions) {
            return $finalInstructions;
        }
        return response()->json(['Empty' => 'No Instructions Available Yet.']);
    }
}
