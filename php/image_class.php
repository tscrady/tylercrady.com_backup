<?php

//ini_set('display_errors', 1); 
//error_reporting(E_ALL | E_STRICT);




class images {
    
    
    var $album_name;//whatever album we are working with atm
    var $album_title;
    var $album_description;
    var $access;
    var $year;
    var $all_album_names;//Can retreive just the album names if neccessary
    var $album_name_links = array();//this contains all the info for all albums
    var $album_images;//This will contain the names of all images in an album
    var $path_to_images = "https://tylercrady.com/images/albums/";
    var $full_path_to_albums = "/home/tylercra/public_html/tylercrady.com/images/albums/";
    
    
    
    
    
    
    //Start off by getting all our album names from the directory and storing details about each album
    function __construct(){
        //Get all the names of our albums
        $this->all_album_names = array_diff(scandir($this->full_path_to_albums), array('..', '.'));
        //Now loop through each album and store info for each album
        foreach($this->all_album_names as $album){
            $album_deets = scandir($this->full_path_to_albums.$album);
            $album_count = count($album_deets)-3;//the number of images in this album
            $xml_info    = simplexml_load_file($this->full_path_to_albums.$album."/info.xml");//getting the access lvl from the xml file
            $access      = (string)$xml_info->access;//access level of this photo album
            $title       = (string)$xml_info->title;//title
            $year        = (string)$xml_info->year;//year
            $album_image = $album_deets[4];//an image from the album
            $link        = array($album, "https://tylercrady.com/images/$album", $this->path_to_images.$album."/".$album_image, $album_count, $access, $title, $year);//all info for an album
            
            $this->album_name_links[] = $link;
            
        }
        
        //Sort the items by year
        usort($this->album_name_links, array($this, 'sort_items'));
        
    }//End constructor
    
    //Simple sorting helper
    public static function sort_items($a, $b){
        return strcmp($b[6] , $a[6]) ;
    }
    
    
    
    //Simply checks if an album exists
    function isAlbum($album){
        return in_array($album, $this->all_album_names);
    }
    
    
    
    
    
    //Assigning all the details for this album object
    function get_album_info(){
        $info                       = simplexml_load_file($this->full_path_to_albums.$this->album_name."/info.xml");
        $this->album_title          = $info->title;
        $this->year                 = $info->year;
        $this->album_description    = $info->description;
        $this->access               = $info->access;
    }
    
    
    
    
    
    //This gets all images names for an album, removes the dots, and the info directory from the list
    function get_album_images(){
        $this->album_images = array_diff(scandir($this->full_path_to_albums.$this->album_name), array('..', '.', 'info.xml'));
    }//end function
    
    
    
    
    
    
    //this returns the html for all images in an album
    function display_album_images(){
        foreach($this->album_images as $image){
                $img = $this->path_to_images.$this->album_name."/".$image;
                //UNCOMMENT THIS WHEN ADDING A NEW ALBUM, JUST FOR THE FIRST RUN - It autorotates all the images to its correct orientation
                        //$this->autoRotateImage($this->full_path_to_albums.$this->album_name."/".$image);
                //UNCOMMENT THIS WHEN ADDING A NEW ALBUM, JUST FOR THE FIRST RUN - It autorotates all the images to its correct orientation
                $image_title = explode(".",$image);
                $image_title = $image_title[0];
                echo      "<a href='".$img."' data-lightbox='$this->album_name' class='album_image_container' data-title='$image_title' style='background-image:url($img)'>"
                        . "<p class='image_title'>$image_title</p> "
                        . "</a>";
        }
    }//end function
    
    
    
    
    
    
    //This returns the html for the albums widget
    function display_albums($album = ""){
        foreach($this->album_name_links as $album_deets){
            
            //Check to see if this is the active album
            $active_class = "";
            if($album === $album_deets[0]){
                $active_class = 'active_album';
            }
            
            if($album_deets[4] == 'open'){//anyone can  view this album
                echo "<a href='$album_deets[1]' style='background-image:url($album_deets[2])' class='album_image_container album_link_container $active_class' ><p class='album_count'>$album_deets[3]</p><p class='image_title'>$album_deets[5]  $album_deets[6]</p></a>";
            }
            else if(isset($_SESSION['logged_in']) && ($_SESSION['access'] == $album_deets[4] || $_SESSION['access'] == 5) ){//This album has a restriction level on it
                echo "<a href='$album_deets[1]' class='album_image_container album_link_container $active_class' ><p class='album_count'>$album_deets[3]</p><img src='$album_deets[2]' class='album_image'><p class='image_title'>$album_deets[5]  $album_deets[6]</p></a>";
            }
                
        }//end foreach
    }//end function
    
    
    
    
    
    //Returns all the html for the image links in the dropdown navigation
    function nav_album_links(){
        $return_string = "";
        $count = 1;
        foreach($this->album_name_links as $album_deets){
            if($count < 4){
                
                if($album_deets[4] == 'open'){//anyone can  view this album
                    $count++;
                $return_string.= "<li><a href='$album_deets[1]'  class='  button ' ><p class='nav_album_title'>$album_deets[5] - $album_deets[6]</p><p class='nav_album_count'>$album_deets[3] images</p></a></li>";
                }
                else if(isset($_SESSION['logged_in']) && ($_SESSION['access'] == $album_deets[4] || $_SESSION['access'] == 5)){//This album has a restriction level on it
                    $count++;
                    $return_string.= "<li><a href='$album_deets[1]'  class='  button' ><p class='nav_album_title'>$album_deets[5] - $album_deets[6]</p><p class='nav_album_count'>$album_deets[3] images</p></a></li>";
                }
            }
            else{
                $return_string.= "<li><a href='/images'  class='  button' ><h2 class='nav_album_title'>View All</h2></a></li>";
                break;
            }
            
           
        }//end foreach
        return $return_string;
    }//end function
    
    
    
    
    //Returns an image path to the correctly oriented image
    function autoRotateImage($image_path) {
        $image = new Imagick($image_path);
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
        $image->writeimage($image_path);
    }
    
    
    
}//end class


