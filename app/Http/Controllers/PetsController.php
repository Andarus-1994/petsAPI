<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class PetsController extends Controller
{
    //


    public function getToken()
    {
        $clientId="f684f07f82ac41aaabafaa42fa9a067f";
        $clientSecret="eUE5pg86dF2V0vz1iubJv84ecWMNDTNd";
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://us.battle.net/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    

    /*
        public function getPets(){
            set_time_limit(1600);
            $tokenCall= PetsController::getToken();
            $token =  json_decode($tokenCall,true)["access_token"];

            $client = new \GuzzleHttp\Client();



    $res = $client->get('https://eu.api.blizzard.com/data/wow/pet/index?namespace=static-eu&locale=en_EU&access_token='.$token);
    $pets =json_decode($res->getBody(),true)['pets'];
    $allPets = [];
    $firebase = (new Factory)
    ->withServiceAccount(__DIR__.'/firebaseKey.json')
    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
    ->createDatabase();

    $database = $firebase;
    $getPetsDB = $database->getReference('Pets')->getValue();
    $allPets = $getPetsDB;

    foreach($pets as $pet){
        $foundPet = false;
       foreach($allPets as $petDB){
        if($pet['id']===$petDB['id'])
        $foundPet = true;
       }
        if($pet['id']> 1499 && !$foundPet){

    $res2 = $client->get('https://eu.api.blizzard.com/data/wow/pet/' .$pet['id'] .'?namespace=static-eu&locale=en_US&access_token=' .
    $token);

       $petDetails = json_decode($res2->getBody(),true);
      if($petDetails["source"] ?? ""){
       $object = new \stdClass();
       $object->id= $pet['id'];
    $object->name = $petDetails["name"];
    $object->icon = $petDetails["icon"];
    $object->is_tradable = $petDetails["is_tradable"];
    $object->is_capturable = $petDetails["is_tradable"];
    $object->description = $petDetails["description"];
    $object->source = ["type"=>$petDetails["source"]["type"], "name"=>$petDetails["source"]["name"]];
    if($petDetails["abilities"][0]["ability"]["id"] ?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][0]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilityOne = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][1]["ability"]["id"] ?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][1]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilityTwo = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][2]["ability"]["id"]?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][2]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilityThree = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][3]["ability"]["id"]?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][3]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilityFour = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][4]["ability"]["id"]?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][4]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilityFive = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][5]["ability"]["id"] ?? "")
    {
    $res3 = $client->get('https://eu.api.blizzard.com/data/wow/media/pet-ability/'. $petDetails["abilities"][5]["ability"]["id"] .'?namespace=static-9.1.0_39069-eu&access_token=' .
    $token);
    $petAbilitySix = json_decode($res3->getBody(),true);
    }
    if($petDetails["abilities"][5]["ability"]["id"] ?? "" && $petDetails["abilities"][4]["ability"]["id"] ?? "" && $petDetails["abilities"][3]["ability"]["id"] ?? "" && $petDetails["abilities"][2]["ability"]["id"] ?? "" && $petDetails["abilities"][1]["ability"]["id"] ?? "" && $petDetails["abilities"][0]["ability"]["id"] ?? ""){
    $object->abilities = [0=>["ability" => ["name" => $petDetails["abilities"][0]["ability"]["name"], "id" => $petDetails["abilities"][0]["ability"]["id"], 'icon' => $petAbilityOne['assets'][0]['value']]],
    1=>["ability" => ["name" => $petDetails["abilities"][1]["ability"]["name"], "id" => $petDetails["abilities"][1]["ability"]["id"], 'icon' => $petAbilityTwo['assets'][0]['value']]],
    2=>["ability" => ["name" => $petDetails["abilities"][2]["ability"]["name"], "id" => $petDetails["abilities"][2]["ability"]["id"], 'icon' => $petAbilityThree['assets'][0]['value']]],
    3=>["ability" => ["name" => $petDetails["abilities"][3]["ability"]["name"], "id" => $petDetails["abilities"][3]["ability"]["id"], 'icon' => $petAbilityFour['assets'][0]['value']]],
    4=>["ability" => ["name" => $petDetails["abilities"][4]["ability"]["name"], "id" => $petDetails["abilities"][4]["ability"]["id"], 'icon' => $petAbilityFive['assets'][0]['value']]],
    5=>["ability" => ["name" => $petDetails["abilities"][5]["ability"]["name"], "id" => $petDetails["abilities"][5]["ability"]["id"], 'icon' => $petAbilitySix['assets'][0]['value']]]];
    }
    $object->battle_pet_type= ["type"=>$petDetails["battle_pet_type"]["type"],];
    $pet=json_decode(json_encode($object),true);
    array_push($allPets,$pet);

      }


    }

    }

    $firebase = (new Factory)
    ->withServiceAccount(__DIR__.'/firebaseKey.json')
    ->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
    ->createDatabase();

    $database = $firebase;

    $newPost = $database->getReference('Pets')->set($allPets);
    return $allPets;
    //    $newPost = $database->getReference('shadowlands/familiarExorcist/sylla/posts')->getValue();
    if($newPost->getValue())
    return response()->json(['success' => 'Your message has been posted']);
    return $newPost->getValue();
        }

    */
    public function getAllPets()
    {
        $firebase = (new Factory)
->withServiceAccount(__DIR__.'/firebaseKey.json')
->withDatabaseUri('https://petsapi-42b65-default-rtdb.firebaseio.com/')
->createDatabase();

        $database = $firebase;
        $getPetsDB = $database->getReference('Pets')->getValue();
        $allPets = $getPetsDB;
        return $allPets;
    }

    public function getPetDetails(Request $request)
    {
        $allPets = $request->allPets;
        $petsArray = (array)$request->pets;
        $searchedPet = $request->petName;
        $type = $request->type;
        $saveDetailsArray =[];
        $typeArraySaved = [];


        $pageNumber= $request->page;



        foreach ($petsArray as $petRequest) {
            foreach ($allPets as $pet) {
                if (!empty($searchedPet)) {
                    if ($petRequest['species']['id'] ===$pet['id'] && str_contains(strtolower($pet["name"]), strtolower($searchedPet))) {
                        $pet['data'] = $petRequest;
                        array_push($saveDetailsArray, $pet);
                        break;
                    }
                }
                if (empty($searchedPet)) {
                    if ($petRequest['species']['id'] ===$pet['id']) {
                        $pet['data'] = $petRequest;
                        array_push($saveDetailsArray, $pet);
                        break;
                    }
                }
            }
        }
        $pageNumberd = ceil(count($saveDetailsArray)/10);
        $saveDetailsArray = collect($saveDetailsArray);
        if ($type) {
            $saveDetailsArray = $saveDetailsArray->sortBy(function ($item, $key) {
                return $item['battle_pet_type']['type'] ;
            });

            foreach ($saveDetailsArray as $key=>$pet) {
                if ($pet['battle_pet_type']['type']===$type) {
                    unset($saveDetailsArray[$key]);
                    array_push($typeArraySaved, $pet);
                }
            }
        }
        $saveDetailsArray = $saveDetailsArray->toArray();
        $saveDetailsArray=array_merge($typeArraySaved, $saveDetailsArray);

        $saveDetailsArray = array_slice($saveDetailsArray, $pageNumber*10, 10);

        return response()->json(['data'=>$saveDetailsArray,"page" =>$pageNumberd]);
    }

   




    public function getSearchedPet(Request $request)
    {
        $allPets = $request->allPets;
        $searchedPet = $request->petName;
        $saveDetailsArray =[];
        $typeArraySaved = [];
        $pageNumber= $request->page;
        
        
        foreach ($allPets as $pet) {
            if (!empty($searchedPet)) {
                if (str_contains(strtolower($pet["name"]), strtolower($searchedPet))) {
                    $pet['data'] = $pet;
                    array_push($saveDetailsArray, $pet);
                }
            }
            if (empty($searchedPet)) {
                array_push($saveDetailsArray, $pet);
            }
        }
    
        $pageNumberd = ceil(count($saveDetailsArray)/10);
        $saveDetailsArray = collect($saveDetailsArray);
       
        $saveDetailsArray = $saveDetailsArray->toArray();
       
    
        $saveDetailsArray = array_slice($saveDetailsArray, $pageNumber*10, 10);
    
        return response()->json(['data'=>$saveDetailsArray,"page" =>$pageNumberd]);
    }
}
