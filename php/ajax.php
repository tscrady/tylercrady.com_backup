<?php
include 'classes.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

$request = $_POST['request'];



if($request === "send_message"){
    
    $regex = '/[a-zA-Z0-9\s\\.\'\",]+/';
    
    $message = $_POST['message'];
    if(preg_match($regex, $message) === 1){
        //send message
        $header =  "From: admin@tylercrady.com\r\n";
        mail("tscrady@gmail.com", "Message from  tylercrady.com", $message, $header);
        echo "success";
    }
    else{
        echo "error";
    }
    
}
else if($request === "create_post"){
    
    $data = $_POST['data'];
    $posts = new posts();
    echo json_encode($posts->create_post($data));
    
}
else if($request === "edit_post"){
    
    $data = $_POST['data'];
    $posts = new posts();
    echo json_encode($posts->edit_post($data));
    
}
else if($request === "change_post_status"){
   
    $data = $_POST['data'];
    $posts = new posts();
    echo json_encode($posts->change_post_status($data));
    
}
else if($request === "change_portfolio_status"){
    
    $data = $_POST['data'];
    $portfolio = new portfolio();
    echo json_encode($portfolio->change_portfolio_status($data));
    
}
else if($request === "create_portfolio"){
    
    $data = $_POST['data'];
    $portfolio = new portfolio();
    echo json_encode($portfolio->create_new_portfolio($data));
    
}
else if($request === "edit_portfolio"){
    
    $data = $_POST['data'];
    $portfolio = new portfolio();
    echo json_encode($portfolio->edit_portfolio($data));
    
}
else if($request === "new_post_category"){
    
    $data = $_POST['data'];
    $post = new posts();
    echo json_encode($post->create_new_post_category($data));
    
}
else if($request === "display_categories"){
    
    $post = new posts();
    echo $post->display_categories();
    
}
