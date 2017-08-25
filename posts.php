<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
include 'php/image_class.php';
include 'php/posts_class.php';
include 'php/portfolio_class.php';

$_SESSION['requested_url'] = "https://tylercrady.com".$_SERVER['REQUEST_URI'];




$posts = new posts();

//first we are going to check if this cat exists and if it has an access restriction on it
if( isset($_GET['cat']) && $posts->isCategory($_GET['cat'])){
        $access = $posts->get_access($_GET['cat']);
        if($access == "open"){
            $oktoshow = true;
        }
        else if(isset($_SESSION['access']) && ($_SESSION['access'] == $access || $_SESSION['access'] == 5) ){
            $oktoshow = true;
        }
        if(!$oktoshow){  
            header("Location: /login");
        } 
}



include 'php/header.php';//This has everything we will need for all pages

?>


<div class='container row' id="posts_page"><!-- this is the main container div -->
            <?php if( (isset($_GET['cat']) && !$posts->isCategory($_GET['cat'])) || !isset($_GET['cat'])){ //either the category isn't set, or it is set, but it isn't a legit category
                echo "<h2 class='section_title'>All Categories</h2>";
                
                $categories = $posts->get_categories();
                
                foreach($categories as $category){
                    $id         = $category['id'];
                    $title      = $category['title'];
                    $url        = $category['url'];
                    $access     = $category['access'];
                    $num_posts  = $category['num_posts'];
                    $last_post  = date('m/d/y', strtotime($category['last_updated']));

                    //Either it's open access, or, session variable is set and is equal access or it's admin access
                    if($access == "open" || ( isset($_SESSION['access']) && ( ($_SESSION['access'] == $access || $_SESSION['access'] == 5) ))){
                        echo "<div class='row blue_container all_categories'>";
                            echo "<a href='$url'><div class='col-xs-12 col-sm-4'><h3> $title</h3></div><div class='col-xs-6 col-sm-4'><p>$num_posts posts</p></div><div class='col-xs-12 col-sm-4'><p>Last Update: $last_post</p></div></a>";
                        echo "</div>";
                    }
                    
                    
                
                }
                
                
                
                
                
            }
            else{//category is set, show posts for this category
                echo "<h2 class='section_title'>".$_GET['cat']."</h2>";
              
        // Now we will loop through the portfolio items now, grab the info of each one and display it. -->
         
        
        $posts_content = $posts->get_all_posts($_GET['cat']);//get content from it
        
        foreach($posts_content as $post){
            
            
            //Lets grab the info we will want to display
            $oktoshow    = false;
            $id          = $post['id'];
            $title       = $post['title'];
            $body        = $post['body'];
            $category    = $post['category'];
            $media_url   = $post['media_url'];
            $media_type  = $post['media_type'];
            $date        = $post['date'];
            
            
        ?>
            
        <div class="row post" id='<?php echo $id;?>'>
            <h2 class="section_title"><?php echo $title." - ".$date;?></h2>
            <?php if($media_type === 'Youtube'){ ?>
                <div class="col-md-6">
                    <div class="videoWrapper"><iframe src='<?php echo $media_url; ?>' frameborder="0" allowfullscreen></iframe></div>
                </div>
                <div class="col-md-6"><p><?php echo $body;?></p></div>
            <?php }else if($media_type === 'Image'){ ?>
                <div class="col-md-6">
                    <div class="posts_img"><img src="<?php echo $media_url; ?>"/></div>
                </div>
                <div class="col-md-6"><p><?php echo $body;?></p></div>
            <?php }else if($media_type === "none"){ ?>
                <div class="col-xs-0 col-md-2"></div>
                <div class="col-xs-11 col-md-8"><p><?php echo $body;?></p></div>
                <div class="col-xs-0 col-md-2"></div>
            <?php } ?>
            
            
        </div>

            
            <?php   } }  ?><!-- end oktoshow check and for each loop -->
        </div><!-- end our main container div -->




<?php include 'php/footer.php'; 

