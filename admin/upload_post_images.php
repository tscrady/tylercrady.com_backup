<?php
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 'On');



$valid_extensions = array('jpeg', 'jpg', 'png', 'JPG', 'JPEG', 'PNG'); // valid extensions
//$path = realpath(dirname(getcwd()));//Upload Path
//echo $path;

if(isset($_FILES['image'])){
    $img = str_replace(" ","_", $_FILES['image']['name']);
    $tmp = $_FILES['image']['tmp_name'];

    
    $date  = date('m-d-Y');
    

    
    // get uploaded file's extension
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    // can upload same image using rand function
//        $final_image = rand(1000,1000000)."_".strtolower($img)."_".$image_number.$ext;
        $final_image = $img;
    // check's valid format
        if(in_array($ext, $valid_extensions)){     
            $path = "/home/tylercra/public_html/tylercrady.com/images/post_images/".$final_image; 
            if(move_uploaded_file($tmp,$path)){
                
                
                
                
                $im = new Imagick("/home/tylercra/public_html/tylercrady.com/images/post_images/".$final_image);
                $d = $im->getImageGeometry(); 
                $w = $d['width']; 
                $h = $d['height']; 
                if( $w < 400 || $h < 400){
                    echo "too small";
                    exit();
                }
                
                //Rotate the image correctly with a magick function I found online
                $im = autoRotateImage($im);
                $im->writeImage("../images/post_images/".$final_image);
                
                
              //All went well, echo out the image path  
              echo "https://tylercrady.com/images/post_images/".$final_image;
            }
            else{
                echo "too big";
            }
        } 
        else{
            echo 'invalid file';
        }
}
else{
    echo "not working";
}





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








?>