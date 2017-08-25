<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
include 'php/classes.php';

//If accessing a forbidden page, after successful login, the user is sent to the page they had previously been trying to access
$_SESSION['requested_url'] = "https://tylercrady.com".$_SERVER['REQUEST_URI'];


//create our images object
$images = new images();

//First check if there is an album trying to be access, if there is, check the permissions
if(isset($_GET['album'])){ 
        
        if($images->isAlbum($_GET['album'])){//first check if it is actually an album
        
            $images->album_name = $_GET['album'];// set the album name
            $images->get_album_info();//retreives the info for this album
            $access = $images->access;
            if($access == "open"){
                $oktoshow = true;
            }
            else if(isset($_SESSION['access']) && ($_SESSION['access'] == $access || $_SESSION['access'] == 5) ){
                $oktoshow = true;
            }

            if(!$oktoshow){  
                header("Location: /login");
            } 
        }//end valid album check
}//end check access


//We are now ready to begin the page
include 'php/header.php';
?>



<!--The images displayed from current album -->
<?php 
    if(isset($_GET['album']) && $images->isAlbum($_GET['album'])){ 
        
        $album = $_GET['album'];//get our album name
        $images->album_name = $album;// set the album name
        $images->get_album_images();//retrieves all images in this album for use later
        $images->get_album_info();//retreives the title and description for this album
        
    
?>

        <!--The albums slider -->
        <div class="container">
            <h2 class='section_title'>Photo Albums</h2>
            <div id="album_container">

                <div id="album_slide">
                <?php 
                    //Pass in the album currently selected if any
                    $images->display_albums($album);
                ?>
                </div>
            </div>
        </div>
        <!--The albums slider -->
        
        
        
        <div class="container">
            <h2 class='section_title'><?php echo $images->album_title;?> <span class='image_year'><?php echo $images->year;?></span></h2>
            <p class='album_description'><?php echo $images->album_description;?></p>
            <div id='image_container'>
            <?php $images->display_album_images(); ?>
            </div>
        </div>
    <?php }
    else{ ?><!-- IF WE ARE HERE, THERE IS NO ALBUM SELECTED, SO JUST SHOW ALL ALBUMS -->
           
        <div class='container row' id="image_page"><!-- this is the main container div -->
            <?php if(isset($_GET['album']) && !$images->isAlbum($_GET['album'])){ ?>
                <h2 class='section_title'>Couldn't find that album, but here are some others</h2>
            <?php }else{ ?>
                <h2 class='section_title'>All Albums</h2>
            <?php } ?>
        <!-- Now we will loop through the portfolio items now, grab the info of each one and display it. -->
        <?php foreach($images->album_name_links as $album){
            
            //Lets grab the info we will want to display
            $oktoshow    = false;
            $title       = $album[5];
            $link        = $album[1];
            $count       = $album[3];
            $access      = $album[4];
            $year        = $album[6];
            $preview_img = $album[2];
          
            if($access == "open"){
                $oktoshow = true;
            }
            else if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 5) ){
                $oktoshow = true;
            }
            
            if($oktoshow){       
                    
        ?>


        
            <a href='<?php echo $link;?>'>
                 <div class='col-xs-6 col-sm-4 col-md-4 '>
                     <div class='blue_container preview_panel' style='background-image:url("<?php echo $preview_img;?>")'>
                         <div class="preview_content_overlay"></div>
                         <div class="preview_content">
                            <h2 class='section_title'><?php echo $title;?></h2>
                            <p><?php echo $count." images";?></p>
                            <p><?php echo $year;?></p>
                         </div>
                     </div>
                 </div>
             </a>
        
        
        
        
        
        <?php  } }?><!-- end the oktoshow check and  for each loop -->
        </div>
    <?php } ?><!-- end the else -->





 <?php include 'php/footer.php'; 



