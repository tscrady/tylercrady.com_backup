<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

include 'dbConnect.php';


class traveler{
     
    
 
    var $name;
    var $text;
    var $latitude;
    var $longitude;
    var $address;
    var $conn;
    var $image;
    
    //A traveler must have a name
    public function __construct($traveler_name) {
        $this->name     = $traveler_name;
        $db             = new dbConnection();
        $this->conn     = $db->conn;
    }
    
    //Set the travelers latitude and longitude properties
    function set_location($latitude, $longitude, $address, $text, $image, $country){
        $this->latitude     = $latitude;
        $this->longitude    = $longitude;
        $this->address      = $address;
        $this->text         = $text;
        $this->image        = $image;
        $this->country      = $country;
    }
    
    //Add the travelers location to the database
    function add_location(){
        
        if($this->image !==""){
            $path_to_image = $this->image ;
            $im = new Imagick($path_to_image);

            //Rename it as country_number_date.ext
            $date           = date('m-d-Y');
            $image_name     = $this->country."_".rand(1,100000)."_".$date;
            $image_name     = str_replace(array(' ', ','), '' , $image_name);
            $info           = getimagesize($path_to_image);
            $ext            = image_type_to_extension($info[2]);
            $new_path_to_image   = "/home/tylercra/public_html/tylercrady.com/travels/images/uploaded_images/".$image_name.$ext; 
            $new_path_to_image2  = "https://tylercrady.com/travels/images/uploaded_images/".$image_name.$ext;
            
            $this->image         = $new_path_to_image2;
            $im->writeImage($new_path_to_image);
            unlink($path_to_image);
        }
        
        //Enter the info to the db
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hours'));
        $query = "insert into locations (name, latitude, longitude, address, description, datetime, path_to_image) values ('$this->name','$this->latitude','$this->longitude','$this->address','$this->text', '$datetime', '$new_path_to_image2')";
        $insert = $this->conn->prepare($query);
        $insert->execute();
        
//        //Send an email to sarah
//        $this->sent_alert();
    }
    
    //Get all locations for a traveler
    function get_pins(){
        
        $return_data = array();
        
        //Our query info
        $query = " select * from locations where name ='$this->name'";
        $locations = $this->conn->prepare($query);
        $locations->execute();
        
        while($row = $locations->fetch(PDO::FETCH_OBJ)){
            
            //Little date/time formatting here
            $datetime           = date_create($row->datetime);
            $date               = date_format($datetime, 'm/d/Y');
            $time               = date_format($datetime, 'g:ia');
            
            //Add all values to temp array
            $temp = array();
            $temp['name']       = $row->name;
            $temp['latitude']   = $row->latitude;
            $temp['longitude']  = $row->longitude;
            $temp['address']    = $row->address;
            $temp['text']       = $row->description;
            $temp['image']      = $row->path_to_image;
            $temp['date']       = $date;
            $temp['time']       = $time;
            $return_data[] = $temp;
            
        }//end while loop
        
        return $return_data;
    }//end function
    
    
    function upload_image($img, $tmp){

        $date = date('m-d-Y');
        // can upload same image using rand function
        $image_number   = "image_".rand(1,100000)."_".$date;
//        $ext            = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $ext            = "jpg";
        $path_to_image  = "/home/tylercra/public_html/tylercrady.com/travels/images/uploaded_images/".$image_number.".".$ext; 
        $response       = "https://tylercrady.com/travels/images/uploaded_images/".$image_number.".".$ext;

        //Link to this object we will need to reference this image later
        $for_data_attr = $path_to_image;

            if(move_uploaded_file($tmp,$path_to_image)){
                
                $im = new Imagick($path_to_image);
                $d = $im->getImageGeometry(); 
                $w = $d['width']; 
                $h = $d['height']; 
                //If the image is bigger than this, we can shrink it down. 
                if($w > 1200 && $h > 1200){
                    //Scale the image back
                    $im->scaleImage(1200, 1200, true);
                    //Re write the image
                    $im->writeImage($path_to_image); 
                }
                
                //Rotate the image correctly with a magick function I found online
                $im = autoRotateImage($im);
                $im->writeImage($path_to_image); 
                
                
                
            }//end if moved
            else{
                $response = "error";
            }
        echo json_encode(array($response, $for_data_attr));
    }//upload image
     
    
    function sent_alert(){
        
        //The headers
        $headers  = "From: tscrady@gmail.com\r\n";
        //$headers .= 'Cc: gmacek@marketingresources.com' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        //The message
        
        $msg = "<div style='width:600px;text-align:left;'   ";
        $msg .= "<h3 style='color:black;font-family:Arial;'>Hello love,</h3> ";
        $msg .= "<p style='color:black;font-family:Arial;'>I have something to show you from <strong>$this->address</strong></p>";
        $msg .= "<p style='color:black;font-family:Arial;font-style:italic'>\"$this->text\"</p>";
        $msg .= "<a href='$this->image' onclick='return:false' style='color:black;font-size:12px;font-family:Arial;display:block;'>Click here to view image.</a>";
        $msg .= "<img alt='$this->country' title='$this->country' src='$this->image'  width='600px' style='display:block;margin-left:auto;margin-right:auto;margin-top:20px;'/>";
        $msg .="<hr/><br/><br/>";
        $msg .= "</div>";
        // use wordwrap() if lines are longer than 80 characters
        $msg = wordwrap($msg,80);

        // send email
//        mail("tcrady@marketingresources.com, bcalvino@slantmarketing.com, ".$am_id.", ".$sales_id,$job." - OOP COST THRESHOLD ALERT",$msg, $headers);
        mail("clum.sarah@gmail.com, steven.crady@tn.gov","tylercrady.com - Travel Update ",$msg, $headers);
//        mail("tcrady@marketingresources.com, bcalvino@slantmarketing.com",$job." - OOP COST THRESHOLD ALERT",$msg, $headers);
        
    }

     
}//end class



//This is a helper function for upload_image() 
    function autoRotateImage($image) {
        $orientation = $image->getImageOrientation();

        switch($orientation) {
            case imagick::ORIENTATION_BOTTOMRIGHT: 
                $image->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case imagick::ORIENTATION_LEFTBOTTOM: 
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
        return $image;
    }
