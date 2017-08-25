<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);









class images {
    
    
    
    var $album_name;
    var $all_album_names;
    var $album_images;
    var $path_to_images = "https://tylercrady.com/tylercrady/images/albums/";
    
    function get_album_names(){
        $this->all_album_names = array_diff(scandir('/home/tylercra/public_html/tylercrady.com/tylercrady/images/albums/'), array('..', '.'));
        return $this->all_album_names;
    }
    
    
    function get_album_images(){
        $this->album_images = array_diff(scandir('/home/tylercra/public_html/tylercrady.com/tylercrady/images/albums/'.$this->album_name), array('..', '.'));
    }
    
    
    
    function display_album_images(){
        foreach($this->album_images as $image){
            $img = $this->path_to_images.$this->album_name."/".$image;
            echo "<a href='".$img."' data-lightbox='$this->album_name' class='album_image_container' data-title='$image'><img class='album_image' src='".$img."'/></a>";
        }
    }
    
    function display_albums(){
        
    }
    
    
    
}


