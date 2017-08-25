<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
if( !isset($_SESSION['access']) || $_SESSION['access'] != 5){
    header("Location: https://tylercrady.com/login");
}


include '../php/classes.php';
include '../php/header.php';

//Create posts object
$posts = new posts();


?>




<div class='container admin_page'>
    
 
    
    <?php 
        $all_posts = $posts->get_all_posts();
        
        foreach($all_posts as $post){
            $title      = $post['title'];
            $date       = $post['date'];
            $category   = $post['category'];
            $id         = $post['id'];
            $status     = $post['status'];
            if($status == 1){
                $active = 'active';
                $active_class = "active_post";
            }
            else{
                $active = 'inactive';
                $active_class = "inactive_post";
            }
            
            echo "<div class='blue_container row all_posts'>";
            echo "<div class='col-xs-12 col-sm-3'><p class='all_posts_title'>$title</p></div>";
            echo "<div class='col-xs-6 col-sm-3'><p>$category</p></div>";
            echo "<div class='col-xs-6 col-sm-2'><p>$date</p></div>";
            echo "<div class='col-xs-6 col-sm-2 edit_post'><a href='https://tylercrady.com/admin/post/$id' class='button'>Edit</a></div>";
            echo "<div class='col-xs-6 col-sm-2 edit_post'><p title='Click to change activity status'  data-status='$status' data-id='$id' class='button change_post_status $active_class'>$active</p></div>";
            echo "</div>";
            
            
        }
    ?>
</div>











<script type="text/javascript" src="https://tylercrady.com/js/admin_ajax.js?v=<?php echo time();?>"></script>
<?php include '../php/footer.php';?>