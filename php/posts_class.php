<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

include_once 'pdo_class.php';




class posts{

    
var $conn;
    
function __construct(){
    
    //When a posts object is created, create the connection
    $pdo = new pdo_connection();
    $this->conn = $pdo->conn;

}

function nav_links(){
    $return_string = "";
    foreach($this->get_categories() as $deets){
        
        $access     = $deets['access'];
        $link       = $deets['url'];
        $num_posts  = $deets['num_posts'];
        $title      = $deets['title'];
        $oktoshow   = false;
        
        
        if($access == 'open'){//anyone can  view this category
            $oktoshow = true;
        }
        else if(isset($_SESSION['logged_in']) && ($_SESSION['access'] == $access || $_SESSION['access'] == 5)){//This category has a restriction level on it
            $oktoshow = true;
        }
        
        if($oktoshow && $num_posts> 0){
            $return_string.= "<li><a href='$link'  class='  button' ><p class='nav_album_title'>$title</p><p class='nav_album_count'>$num_posts Posts</p></a></li>";
        }
    }//end foreach
    return $return_string;
}








public static function sort_items($a, $b){
    return strcmp($b['date'] , $a['date']) ;
}

function isCategory($category_in_question){
    foreach($this->get_categories() as $category){
        if($category_in_question === strtolower($category['title']) ){
            return true;
        }
    }
    return false;
}

/*
 * Returns the id, title, and url of all post categories
 * can be used for the admin adding categories, or for the nav to select categories to view
 */
function get_categories($admin = false){
    
    $return_array = array();
    $query = 'select * from post_categories order by last_updated desc';
    $posts = $this->conn->prepare($query);
    $posts->execute();
    
    while($row = $posts->fetch(PDO::FETCH_OBJ)){
        
        
        $temp = array(
            'id'            => $row->id,
            'title'         => $row->category_name,
            'url'           => $row->category_url,
            'access'        => $row->access,
            'num_posts'     => $this->get_number_of_posts($row->id),
            'last_updated'  => $row->last_updated
        );
        if($temp['num_posts'] > 0){
            $return_array[] = $temp;
        }
        else if($admin){
            $return_array[] = $temp;
        }
    }
    return $return_array;
}//end function


/*
 * takes a category id and return the access level for it
 */
function get_access($category){
    $id = $this->get_category_id($category);
    $query = "select access from post_categories where id= '$id' ";
    $posts = $this->conn->prepare($query);
    $posts->execute();
    while($row = $posts->fetch(PDO::FETCH_OBJ)){
        return $row->access;
    }
}


/*
 * This will return all posts for all categories unless 
 * a category is specified, then it will return all posts
 * for that specific categories
 */
function get_all_posts($category = false){
    
    $return_array = array();
    
    if($category){
        $category = $this->get_category_id($category);
        $query = "select * from posts where category_id ='$category' and status=1 order by date desc";
    }else{
        $query = "select * from posts order by date desc";
    }
    $get_posts = $this->conn->prepare($query);
    $get_posts->execute();
    
    while($row = $get_posts->fetch(PDO::FETCH_OBJ)){
        $temp = array();
        $temp['id']         = $row->id;
        $temp['title']      = $row->title;
        $temp['category']   = $this->get_category_name($row->category_id);
        $temp['body']       = $row->body;
        $temp['date']       = date('m/d/Y', strtotime($row->date) );
        $temp['media_url']  = $row->media_url;
        $temp['media_type'] = $row->media_type;
        $temp['status']     = $row->status;
        $return_array[] = $temp;
    }
    return $return_array;
}//end get all posts






/*
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 * STRICTLY ADMIN FUNCTIONS ---------------------------------------------------------------------
 */

/*
 * Admin function: this edits a post in the posts table
 */
function edit_post($data){
    
    $title      = $data['title'];
    $body       = $data['body'];
    $category   = $data['cat'];
    $m_type     = $data['m_type'];
    $m_url      = $data['m_url'];
    $id         = $data['post_id'];
    $date       = $data['date'];
    
    $query  = "update posts set title=:title, category_id='$category', body=:body, media_type='$m_type', media_url=:m_url, date='$date' where id=:id";
    $insert = $this->conn->prepare($query);
    $insert->bindParam(":title", $title);
    $insert->bindParam(":body", $body);
    $insert->bindParam(":m_url", $m_url);
    $insert->bindParam(":id", $id);
    
    //Update the categories table to show the latest update for this category
    $update_timestamp_query = "update post_categories set last_updated = NOW() where id='$category'";
    $update_timestamp_obj = $this->conn->prepare($update_timestamp_query);
    $update_timestamp_obj->execute();
    
    if($insert->execute()){
        return "success";
    }
    else{
        return $this->conn->errorCode();
    }
    
}//end function




/*
 * Admin function: this returns all the fields for a post, so I can edit the post
 */
function get_post($id){
    
    $query = "select * from posts where id=:id and status=1";
    $get_post = $this->conn->prepare($query);
    $get_post->bindParam(":id", $id);
    $get_post->execute();
    
    $temp = array();
    while($row = $get_post->fetch(PDO::FETCH_OBJ)){
        $temp['title']  = $row->title;
        $temp['cat']    = $this->get_category_name($row->category_id);
        $temp['cat_id'] = $row->category_id;
        $temp['m_type'] = $row->media_type;
        $temp['m_url']  = $row->media_url;
        $temp['body']   = $row->body;
        $temp['date']   = $row->date;
    }
    return $temp;
}




/*
 * These are both helper function to get the category id
 * if the category title is supplied, and vice versa
 */
function get_category_name($id){
    $query = "select category_name from post_categories where id='$id'";
    $get  = $this->conn->prepare($query);
    $get->execute();
    return $get->fetch(PDO::FETCH_OBJ)->category_name;
}
function get_category_id($cat_title){
    $query = "select id from post_categories where category_name='$cat_title'";
    $get  = $this->conn->prepare($query);
    $get->execute();
    return $get->fetch(PDO::FETCH_OBJ)->id;
}

/*
 * helper function, return the number of posts for a category
 */
function get_number_of_posts($category){
    $query = "select count(*) as count from posts where category_id = '$category' and status=1";
    $count = $this->conn->prepare($query);
    $count->execute();
    return $count->fetch(PDO::FETCH_OBJ)->count;
}

/*
 * Admin function: this inserts a new post into the posts table
 */
function create_post($data){
    
    $return_array = array(
        "status"      => "",
        "new_post_id" => ""
    );
    
    $title      = $data['title'];
    $body       = $data['body'];
    $category   = $data['cat'];
    $m_type     = $data['m_type'];
    $m_url      = $data['m_url'];
    $date       = $data['date'];
    
    $query  = "insert into posts (title, category_id, body, media_type, media_url, date) values (:title,'$category',:body,'$m_type',:m_url,'$date')";
    $insert = $this->conn->prepare($query);
    $insert->bindParam(":title", $title);
    $insert->bindParam(":body", $body);
    $insert->bindParam(":m_url", $m_url);
    if($insert->execute()){
       $return_array['status']      =  "success";
       $return_array['new_post_id'] =  $this->conn->lastInsertId();
    }
    else{
        $return_array['status'] = $this->conn->errorCode();
    }
    
    return $return_array;
    
}//end function

/*
 * Admin function: switches the status of a post between active/inactive
 */
function change_post_status($data){
    $id = $data['id'];
    $current_status = $data['status'];
    $return_array = array(
        "status"      => "",
        "new_status"  => "",
        "status_text" => ""
    );
    
    
    if($current_status == 1){
        $new_status = 0;
        $return_array['new_status'] = $new_status;
        $return_array['status_text'] = 'inactive';
    }
    else{
        $new_status = 1;
        $return_array['new_status'] = $new_status;
        $return_array['status_text'] = 'active';
    }
    
    $query = "update posts set status ='$new_status' where id='$id'";
    $update = $this->conn->prepare($query);
    if($update->execute()){
        $return_array['status'] = 'success';
    }
    else{
        $return_array['status'] = "error";
    }
    
    return $return_array;
}//end function


/*
 * Admin function: returns the html for the access categories
 */
function display_access_categories(){
    
    $html_string = "";
    
    $query = "select * from access_levels";
    $access = $this->conn->prepare($query);
    $access->execute();
    while($row = $access->fetch(PDO::FETCH_OBJ)){
        $html_string .= "<option value='$row->id' >$row->level, $row->access_group</option>";
    }
    echo $html_string;
}//end function


/*
 * Admin function: display the html for the categories dropdown
 */
function display_categories($cat_id = false){
    
    //Create our object
    $categories = $this->get_categories(true);//passing true means it is an admin call
    //Initialize our return string
    $return_string = "";
    //Populate the return string
    foreach($categories as $category){
        $id     = $category['id'];
        $title  = $category['title'];
        if($category && $cat_id == $id ){
            $return_string.= "<option value='$id' selected>$title</option>";
        }
        else{
            $return_string.= "<option value='$id'>$title</option>";
        }
    }
    //Return the return string
    echo $return_string;
}


/*
 * Admin function: add 
 */
function create_new_post_category($data){
    $name   = $data['name'];
    $access = $data['access'];
    $url    = "https://tylercrady.com/".str_replace(" ", "_", strtolower($name));
    
    $query = "insert into post_categories (category_name, access, category_url) values ('$name', '$access', '$url')";
    $insert = $this->conn->prepare($query);
    if($insert->execute()){
        return array("status" => "success", "category" => $this->conn->lastInsertId());
    }
    else{
        return array("status" => "error");
    }
    
}//end function



}

