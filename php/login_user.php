<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();

// Couple variables
$full_path_to_users = "/home/tylercra/public_html/tylercrady.com/php/users/";
$json_response = array(
    "status"  => "",
    "message" => "",
    "url"     => "/images"  
);

//Start checking the login info
if( isset($_POST['username']) && isset($_POST['password']) ){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(file_exists($full_path_to_users.$username.".xml")){
        //retreive the users info from the xml file
        $user_info = simplexml_load_file($full_path_to_users.$username.".xml");
        if($user_info->password == $password || $user_info->password == ''){
            //Set our return variable messages
                $json_response['status']    = "success";
                $json_response['message']   = "Login Successful";
            //Set our session vars
                $_SESSION['logged_in']      = "true";
                $_SESSION['user']           = $username; 
                $_SESSION['access']         = (string)$user_info->access; 
                
                if($username === "portfolio"){
                    $json_response['url'] = "/portfolio";
                }
//                else if(isset($_SESSION['requested_url'])){
//                    $json_response['url'] = $_SESSION['requested_url'];
//                }
                else if($username === 'tcrady'){
                    $json_response['url'] = "/admin/all_portfolio.php";
                }
                else{
                    $json_response['url'] = "/images";
                }
        }
        else{//the password was incorrect
            $json_response['status'] = "error";
            $json_response['message'] = "Incorrect Password.";
        }
    }
    else{//the file doesn't exist with that username
        $json_response['status'] = "error";
        $json_response['message'] = "Incorrect Username.";
    }
}
else if(isset($_POST['logout'])){
    session_destroy();
    $json_response['status'] = "success";
}
else{//we didn't get a username and password to check against
    $json_response['status'] = "error";
}


//Return our results
echo json_encode($json_response);

