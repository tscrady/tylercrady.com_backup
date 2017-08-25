<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
if( !isset($_SESSION['access']) || $_SESSION['access'] != 5){
    header("Location: https://tylercrady.com/login.php");
}


include '../php/classes.php';
include '../php/header.php';

$portfolio = new portfolio();



$edit  = false;
$portfolio_id = 0;//If post id stays 0, it means we are creating a new post
if(isset($_GET['id'])){

    $portfolio_data  = $portfolio->get_portfolio($_GET['id']);
    
    if(count($portfolio_data) > 0){//make sure we actually pulled in a post, other wise just show create post page per ush
        $portfolio_id    = $_GET['id'];//if this gets set here, it means we are editing a new post
        $edit            = true;

        $title          = $portfolio_data['title'];
        $body           = $portfolio_data['body'];
        $technology     = $portfolio_data['technology'];
        $project_type   = $portfolio_data['project_type'];
        $date           = $portfolio_data['date'];
        $access         = $portfolio_data['access'];
        if(array_key_exists('images', $portfolio_data)){
            $portfolio_images         = $portfolio_data['images'];
        }
        
    }
}


?>





<div class="container admin_page">
    <div class="blue_container">
        
        <?php if($edit){ ?>
        <a style='display:block;margin:10px auto 40px auto;width:100px;text-align:center'  class='button' target='_blank' href='https://tylercrady.com/portfolio/<?php echo $portfolio_id;?>'><p>View Post</p></a>
        
        <?php } ?>
        
        
        <form id="portfolio_form" method="POST">
             <input type="text" hidden id='portfolio_id' value="<?php echo $portfolio_id;?>"/><!-- ------------ ajax checks this to see if editing a post or creating a new one -->
    
    <div class="row">
        <div class="col-xs-12 col-sm-5">
            <p class="input_label">Title</p>
            <input type="text" id="portfolio_title" value="<?php if($edit){echo $title;}?>">
        </div>
        <div class="col-xs-12 col-sm-3">
            <p class="input_label">Project Type</p>
            <input type="text" id="project_type" value="<?php if($edit){echo $project_type;}?>">
        </div>
        <div class="col-xs-6 col-sm-2">
            <p class="input_label">Date</p>
            <input type="text"  id="datepicker" value="<?php if($edit){ echo $date;}else{ echo date('Y-m-d');}?>">
        </div>
        <div class="col-xs-6 col-sm-2">
            <p class="input_label">Access</p>
            <select type="text"  id="portfolio_access">
                <?php if($edit){echo "<option selected value='$access'>$access</option>";}?>
                <option value="open">open - Anyone</option>
                <option value="3">3 - Recruiters</option>
                <option value="5">5 - Only me</option>
            </select>
        </div>
    </div>
    
        
        <br/>
        <br/>
        <br/>
        
        <div class="row">
            <!-- this is added in admin_ajax.js when I click the add image button -->
            <div id="portfolio_images">
                <?php 
                if($edit && isset($portfolio_images)){
                    foreach($portfolio_images as $image){
                        
                        $id          = $image['id'];
                        $description = $image['description'];
                        $image_path  = $image['image_path'];
                        $image_num   = $image['image_number'];
                    ?>
                        <div class="image_container">
                        <div class="col-xs-12 col-sm-6">
                            <p class="input_label">Image Description <?php echo $image_num;?></p>
                            <textarea class="description"><?php echo $description;?></textarea>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p class="input_label">Image <?php echo $image_num;?></p>
                            <input data-image_number='<?php echo $image_num;?>' data-id="<?php echo $id;?>" data-image_path='<?php echo $image_path;?>' style='background-image:url(<?php echo $image_path;?>)' type="file" class="portfolio_file">
                            <image class='loading portfolio_loading' id='loading<?php echo $image_num;?>' src='https://tylercrady.com/images/loading.gif'/>
                            <image class='loading portfolio_loading' id='success<?php echo $image_num;?>' style='display:inline-block' src='https://tylercrady.com/images/upload_success.png'/>
                        </div>
                        </div>
                <?php } }//this ends the loops, and the if statement ?>
            
            </div>
        </div>
        <p class="button" id="add_image" >Add Image</p>
      
        
    <div class="row">
        <div class="col-xs-12">
            <p class="input_label">Project Description</p>
            <textarea id="portfolio_body" placeholder="Content..."><?php if($edit){echo $body;}?></textarea>
        </div>
    </div>
        
    <div class="row">
        <div class="col-xs-12">
            <p class="input_label">Technologies Used</p>
            <input type="text" id="technologies" value="<?php if($edit){echo $technology;}?>"/>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <input type="submit" value="<?php if($edit){ echo 'Save Edit';} else { echo 'Create Portfolio';}?>" class="button" id="create_new_portfolio">
        </div>
    </div>
        
    
        </form>
    </div><!-- end blue container div -->
</div><!-- end row div -->





<script type="text/javascript" src="https://tylercrady.com/js/admin_ajax.js?v=<?php echo time();?>"></script>

<?php include '../php/footer.php'; ?>