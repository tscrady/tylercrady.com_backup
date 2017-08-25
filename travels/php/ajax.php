<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);

include 'traveler.php';

if(isset($_POST['request'])){
    
    //See what the requst is
    $request = $_POST['request'];
    
    
    //Here we create a traveler, set their location, then add their location to the db
    if($request === "set_location"){
        
        //Grab our data from our post var
        $data       = $_POST['location_info'];
        $name       = $data['name'];
        $latitude   = $data['latitude'];
        $longitude  = $data['longitude'];
        $text       = $data['text'];
        $image      = $data['img'];
        
        //Get the address from lat/longs from google, thanks google
        $latlng         = $latitude.",".$longitude;
        $address_info   = get_address($latlng);
        $address        = $address_info[0];
        $country        = $address_info[1];
        
        //Create our traveler
        $traveler   = new traveler($name);
        
        //Set the location of our traveler
        $traveler->set_location($latitude, $longitude, $address, $text, $image, $country);
        
        //Add the location of our traveler to the db
        $traveler->add_location();
        
        echo $address;
    }
    else if($request === 'get_pins'){
        $travelers      = $_POST['travelers'];
        $all_locations  = array();
        
        //Create out travelers and get their locations
        for($i = 0; $i < count($travelers); $i++){
            //Create our traveler object
            $traveler_obj   = new traveler($travelers[$i]);
            //Get their pins
            $pins       = $traveler_obj->get_pins();
            //Add to our return array
            $all_locations[$travelers[$i]] = $pins;
        }

        echo json_encode($all_locations);
    }
    else if($request === 'image_upload'){
        
        //Create our temp traveler
        $traveler = new traveler("temp");
        
        if(isset($_FILES['image'])){
            $img  = $_FILES['image']['name'];
            $tmp  = $_FILES['image']['tmp_name'];
            $size = $_FILES['image']['size'];
            if($size > 10000000){
                echo 'too big';
                exit();
            }
            $status = $traveler->upload_image($img, $tmp);
            
        }//End if image
        
    }
}
else{
    echo "error";
}





//Returns a nicely formatted address form a lat,lng pair
function get_address($latlng){
    // google map geocode api url
        $url = "http://maps.google.com/maps/api/geocode/json?latlng={$latlng}";
    // get the json response
        $resp_json = file_get_contents($url);
    // decode the json
        $resp = json_decode($resp_json, true);
    // response status will be 'OK', if able to geocode given latlng 
    if($resp['status']=='OK'){
        //Return the address all formatted nicely, thanks google.
        return array($resp['results'][0]['formatted_address'], $resp['results'][9]['formatted_address']);
    }
}