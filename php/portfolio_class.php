<?php

//ini_set('display_errors', 1); 
//error_reporting(E_ALL | E_STRICT);


include_once 'pdo_class.php';//Include our DB connection


class portfolio{
    
    var $conn;
    


    
    /*
     * Construct just creates a PDO object
     */
    function __construct(){
        
        $pdo = new pdo_connection();
        $this->conn = $pdo->conn;
               
    }//end construct
    
    
   

   /*
    * This is used for the admin panel and for the navigation.
    * It returns the basic data for all the portfolio entries as well as a preview image.
    * If this is called with 'true' passed as a parameter, it is coming from the admin panel
    */
    function get_portfolio_links($admin = false){

        $return_array = array();
        
        if($admin){
            $query = "select * from portfolio   order by date desc";
        }else{
            $query = "select * from portfolio where status=1 order by date desc";
        }
        $get_portfolio_info = $this->conn->prepare($query);
        $get_portfolio_info->execute();
        while($row = $get_portfolio_info->fetch(PDO::FETCH_OBJ)){
            
            $id                     = $row->id;
            $temp['id']             = $id;
            $temp['title']          = $row->title;
            $temp['link']           = "https://tylercrady.com/portfolio/".$id;
            $temp['date']           = date('M Y', strtotime($row->date));
            $temp['type']           = $row->project_type;
            $temp['access']         = $row->access;
            $temp['status']         = $row->status;
            
            //Grab the preview image
            $query2 = "select image_url as url from portfolio_images where portfolio_id ='$id' and image_number=1 ";
            $get_image = $this->conn->prepare($query2);
            $get_image->execute();
            $temp['preview_image']  = $get_image->fetch(PDO::FETCH_OBJ)->url;
            
            $return_array[] = $temp;
        }
        
        
        return $return_array;
    }//end function
    
    
    
    /*
     * This displays the html for the dropdown navigation
     */
    function display_portfolio_navigation(){
        
        $portfolio_links = $this->get_portfolio_links();
        
        $return_string = "";
        $count = 1;
        
        foreach($portfolio_links as $portfolio){
            
            //Lets grab the info we will want to display
            $oktoshow    = false;
            $title       = $portfolio['title'];
            $link        = $portfolio['link'];
            $date        = date('M Y', strtotime($portfolio['date']));
            $preview_img = $portfolio['preview_image'];
            $type        = $portfolio['type'];
            $access      = $portfolio['access'];
            
            if($access == "open"){
                $oktoshow = true;
            }
            else if(isset($_SESSION['access']) && ($_SESSION['access'] == $access || $_SESSION['access'] == 5) ){
                $oktoshow = true;
            }
            
            if($count < 4){
                $count++;
                if($oktoshow){
                    
                    $return_string.= "<li><a href='$link'  class='  button' ><p class='nav_album_title'>$title</p><p class='nav_album_count'>$date</p></a></li>";
                }
            }
            else{
                $return_string.= "<li><a href='/portfolio'  class='  button' ><h2 class='nav_album_title'>View All</h2></a></li>";
                break;
            }
            
            
            
        }//loop through all portfolio links
        return $return_string;
    }//end function 
    
    
    
    
    
    /*
     * this returns the html for all images in a portfolio
     */
    function display_portfolio_slider2($id){
            
            $query = "select count(*) as count from portfolio_images where portfolio_id='$id' and status=1";
            $get_count = $this->conn->prepare($query);
            $get_count->execute();
            $count = $get_count->fetch(PDO::FETCH_OBJ)->count;
            
            //First make sure we have images
            if($count > 0){
                
                //now get the images for this portfolio item
                $query = "select * from portfolio_images where portfolio_id=:id and status=1 order by image_number";
                $get_images = $this->conn->prepare($query);
                $get_images->bindValue(":id", $id);
                $get_images->execute();
                
                
                $index = 1;
                while($row = $get_images->fetch(PDO::FETCH_OBJ)){
                    $description    = $row->image_description;
                    $image_path     = $row->image_url;
                    $image_number   = $row->image_number;


                    if($index === 1){//if this is the start, echo out our starting div html
                        //This is the start of the slider with the first image being shown
                        echo "<div class='portfolio_slider'> ";
                        
                        echo "<div class='portfolio_main_image'><img src='$image_path' ><p class='image_description'>".$description."</p></div>";
                        //This is our thumbnail container and slider
                        echo "<div class='thumbnail_container'>";
                        echo "<div class='thumbnail_slider'>";
                    }
                                if($count > 1){
                                    //If this is the first image in the series, have it be selected
                                    $selected = '';
                                    if($index === 1){
                                        $selected = 'active_portfolio_thumb';
                                    }
                                    //Displaying the image
                                    echo "<div class='portfolio_thumb $selected'  title='$description' style='background-image:url($image_path)' data-src='$image_path' data-description='$description'></div>";

                                    //Increase our index for the descriptions which are in image_descriptions.xml
                                    $index++;
                                }

                }
                echo "</div></div>";//Ending the thumbnail and slider div
                echo "</div>";//Just ending our main slider div  
            }
            else{
                
            }
    }//end function
    
    
    
    
    /*
     * Check if portfoliio exists
     */
    function is_portfolio($id){
        $query = "select count(*) as count from portfolio where id = '$id' and status=1";
        $count = $this->conn->prepare($query);
        $count->execute();
        $exists =  $count->fetch(PDO::FETCH_OBJ)->count;
        if($exists == 1){
            return true;
        }
        else{
            return false;
        }
    }
    
    
    /*
     * Admin function as well: this returns all the fields for a post, so I can edit the post
     */
    function get_portfolio($id){

        $query = "select * from portfolio where id=:id and status=1";
        $get_post = $this->conn->prepare($query);
        $get_post->bindParam(":id", $id);
        $get_post->execute();

        $return_array = array();
        while($row = $get_post->fetch(PDO::FETCH_OBJ)){
            $return_array['title']           = $row->title;
            $return_array['project_type']    = $row->project_type;
            $return_array['body']            = $row->body;
            $return_array['date']            = $row->date;
            $return_array['technology']      = $row->technology;
            $return_array['status']          = $row->status;
            $return_array['access']          = $row->access;
        }

        //now get the images for this portfolio item
        $query2 = "select * from portfolio_images where portfolio_id=:id and status=1 order by image_number";
        $get_images = $this->conn->prepare($query2);
        $get_images->bindValue(":id", $id);
        $get_images->execute();

        while($row = $get_images->fetch(PDO::FETCH_OBJ)){
            $temp['id']             = $row->id;
            $temp['description']    = $row->image_description;
            $temp['image_path']     = $row->image_url;
            $temp['image_number']   = $row->image_number;
            $return_array['images'][] = $temp;
        }
    
    return $return_array;
}//end get_portfolio
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    //ADMIN FUNCTIONS --------------------------------------------------
    
    
    /*
     * This will take all information from a blank portfolio form and enter it into tables 'portfolio' and 'portfolio_images'
     */
    function create_new_portfolio($data){
        
        $return_array = array(
            "status"            => "",
            "new_portfolio_id"  => ""
        );
    
        $title          = $data['title'];
        $body           = $data['body'];
        $technology     = $data['technology'];
        $project_type   = $data['project_type'];
        $date           = $data['date'];
        $access         = $data['access'];
        if(array_key_exists('images', $data)){
            $images     = $data['images'];
        }

        //Portfolio query
        $query  = "insert into portfolio (title, body, project_type, date, technology, access) values (:title,:body,:project_type,'$date', :technology, '$access')";
        $insert = $this->conn->prepare($query);
        $insert->bindValue(":title", $title, PDO::PARAM_STR);
        $insert->bindValue(":body", $body, PDO::PARAM_STR);
        $insert->bindValue(":technology", $technology);
        $insert->bindValue(":project_type", $project_type);

        //image query
        $query2 = "insert into portfolio_images (portfolio_id, image_url, image_description, image_number)  values (:portfolio_id, :image_url, :image_description, :image_number)";
        $insert_image = $this->conn->prepare($query2);

        if($insert->execute()){

           $return_array['status']           = "success";
           $portfolio_id                     = $this->conn->lastInsertId();
           $return_array['new_portfolio_id'] = $portfolio_id;

            if(isset($images)){
                //now insert the images
                foreach($images as $image){
                    $description  = $image[0];
                    $image_path   = $image[1];
                    $image_num    = $image[2];

                    $insert_image->bindValue(":portfolio_id", $portfolio_id);
                    $insert_image->bindValue(":image_url", $image_path);
                    $insert_image->bindValue(":image_description", $description, PDO::PARAM_STR);
                    $insert_image->bindValue(":image_number", $image_num);
                    $insert_image->execute();
                }//end foraach image loop
            }


        }
        else{
            $return_array['status'] = $this->conn->errorCode();
        }

        return $return_array;
    }//end function
    
    
    
    
    /*
     * Just as the name suggests, this will edit a portfolio item. 
     * It's very similar to create_portfolio, but I wanted to some extra stuff with checking if images are uploaded or not.
     */
    function edit_portfolio($data){
        
        $return_array = array(
            "status"            => "",
        );
        $portfolio_id   = $data['portfolio_id'];
        $title          = $data['title'];
        $body           = $data['body'];
        $technology     = $data['technology'];
        $project_type   = $data['project_type'];
        $date           = $data['date'];
        $access         = $data['access'];
        if(array_key_exists('images', $data)){//It's possible to have 0 images, so we must check for that
            $images     = $data['images'];
        }

        //Portfolio query
        $query  = "update portfolio set title=:title, body=:body,  project_type=:project_type, date='$date', technology=:technology, access='$access' where id='$portfolio_id'";
        $update = $this->conn->prepare($query);
        $update->bindValue(":title", $title, PDO::PARAM_STR);
        $update->bindValue(":body", $body, PDO::PARAM_STR);
        $update->bindValue(":technology", $technology);
        $update->bindValue(":project_type", $project_type);

        //new image query
        $query2 = "insert into portfolio_images (portfolio_id, image_url, image_description, image_number)  values (:portfolio_id, :image_url, :image_description, :image_number)";
        $insert_image = $this->conn->prepare($query2);
        
        //update image query
        $query3 = "update portfolio_images set portfolio_id = :portfolio_id, image_url = :image_url, image_description = :image_description, image_number = :image_number where id=:id";
        $update_image = $this->conn->prepare($query3);

        if($update->execute()){

            $return_array['status'] = "success";

            //now insert the images if it is a new image only, also if there are any images to start with
            if(isset($images)){
                foreach($images as $image){
                   $description  = $image[0];
                   $image_path   = $image[1];
                   $image_num    = $image[2];
                   $id           = $image[3];

                    if(!$this->image_exists($portfolio_id, $image_path)){
                        $insert_image->bindValue(":portfolio_id", $portfolio_id);
                        $insert_image->bindValue(":image_url", $image_path);
                        $insert_image->bindValue(":image_description", $description);
                        $insert_image->bindValue(":image_number", $image_num);
                        $insert_image->execute();
                    }
                    else{
                        $update_image->bindValue(":id", $id);
                        $update_image->bindValue(":portfolio_id", $portfolio_id);
                        $update_image->bindValue(":image_url", $image_path);
                        $update_image->bindValue(":image_description", $description, PDO::PARAM_STR);
                        $update_image->bindValue(":image_number", $image_num);
                        $update_image->execute();
                    }
                }//end foraach image loop
            }//end image set check


        }
        else{
            $return_array['status'] = $this->conn->errorCode();
        }

        return $return_array;
    }//end function





/*
 * switches the status of a portfolio between active/inactive
 */
function change_portfolio_status($data){
    
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
    
    $query = "update portfolio set status ='$new_status' where id='$id'";
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
     * This is a helper function for edit_portfolio() to see if an image exists for a portfolio, before inserting it again
     */
    function image_exists($portfolio_id, $image_path){
        $query = "select count(*) as count from portfolio_images where portfolio_id='$portfolio_id' and image_url='$image_path'";
        $get_count = $this->conn->prepare($query);
        $get_count->execute();  
        $count = $get_count->fetch(PDO::FETCH_OBJ)->count;
        if($count > 0){
            return true;
        }
        else{
            return false;
        }
    }//end function
    
    
    
    
    
    
}

