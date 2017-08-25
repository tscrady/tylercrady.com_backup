<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
if( !isset($_SESSION['access']) || $_SESSION['access'] != 5){
    header("Location: https://tylercrady.com/login.php");
}


include '../php/classes.php';
include '../php/header.php';

//Create posts object
$posts = new posts();

$edit  = false;
$post_id = 0;//If post id stays 0, it means we are creating a new post
if(isset($_GET['id'])){

    $post_data  = $posts->get_post($_GET['id']);
    
    if(count($post_data) > 0){//make sure we actually pulled in a post, other wise just show create post page per ush
        $post_id    = $_GET['id'];//if this gets set here, it means we are editing a new post
        $edit       = true;

        $title      = $post_data['title'];
        $body       = $post_data['body'];
        $m_type     = $post_data['m_type'];
        $category   = $post_data['cat'];
        $cat_id     = $post_data['cat_id'];
        $m_url      = $post_data['m_url'];
        $date       = $post_data['date'];
        $date_f     = date('m/d/Y', strtotime($post_data['date']) );
        $post_link  = "https://tylercrady.com/posts/".strtolower($category)."/#".$post_id;
    }
}

?>



<div class="container admin_page " id='admin_new_post'>
    <div class="blue_container">
    
        
        <a style='display:block;margin:10px auto 40px auto;width:100px;text-align:center'  href='<?php echo $post_link ;?>' target="_blank" class='button'>View Post</a>
        
        
    <form id="create_post" method="POST">
        <input type="text" hidden id='post_id' value="<?php echo $post_id;?>"/><!-- ------------ ajax checks this (on form submit) to see if editing a post or creating a new one -->
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <p class="input_label">Post Title</p>
                <input type="text" id="post_title" <?php if($edit){ echo "value='$title'";}?>>
            </div>
            <div class="col-xs-12 col-sm-4">
                <p class="input_label">Post Category</p>
                <select id="post_category">
                    <?php
                        $posts->display_categories($cat_id);
                    ?>
                    <option value="new_category">New Category</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-4">
                <p class="input_label">Date</p>
                <input type="text" id="datepicker" value="<?php if($edit){ echo $date;}else{ echo date('Y-m-d');}?>">
            </div>
        </div>
       
        <div class="row">
            <div class="col-xs-12 col-sm-8 post_media" id="media_none" style="<?php if($edit && $m_type === "none"){ echo "display:inline-block;";}?>>
                <p class="input_label"></p>
                
            </div>
            <div class="col-xs-12 col-sm-8 post_media" id="media_youtube" >
                <p class="input_label">Media URL</p>
                <input type="text" id="media_youtube_url" <?php if($edit && $m_type === "Youtube"){ echo "value='$m_url'";}else if(!$edit){echo "value='https://www.youtube.com/embed/C0DPdy98e4c'";}?>>
            </div>
            <div class="col-xs-12 col-sm-8 post_media" id="media_image" style="<?php if($edit && $m_type === "Image"){ echo "display:inline-block;";}?>">
                <p class="input_label">Upload Image</p>
                <input id="media_image_upload" type="file" data-image_path="<?php if($edit && $m_type === "Image"){ echo $m_url;}?>" style='<?php if($edit && $m_type === "Image"){ echo "background-image:url($m_url)";}?>' >
                <image class='loading ' id='image_loading' src='https://tylercrady.com/images/loading.gif'/>
                <image class='loading ' id='image_success' style="<?php if($edit && $m_type === "Image"){ echo "display:inline-block;";}?>" src='https://tylercrady.com/images/upload_success.png'/>
            </div>
            
            <div class="col-xs-12 col-sm-4">
                <p class="input_label">Media Type</p>
                <select id="media_type">
                    <option value='Youtube' <?php if($edit && $m_type === "Youtube"){ echo "selected";}?>>Youtube</option>
                    <option value='Image' <?php if($edit && $m_type === "Image"){ echo "selected";}?>>Image</option>
                    <option value='none' <?php if($edit && $m_type === "none"){ echo "selected";}?>>none</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <textarea id="post_body" placeholder="Content..." ><?php if($edit){ echo $body;}?></textarea>
            </div>
            <div class="col-xs-12">
                <?php if($edit){ ?>
                    <input type="submit" class='button' value="Save Edit" id="create_new_post"/>
                <?php }else{ ?>
                    <input type="submit" class='button' value="Create Post" id="create_new_post"/>
                <?php } ?>
            </div>
        </div>
        
    </form>
    </div>
    

    
    
    <div id="add_post_category" class="overlay blue_container">
        <i class="close_overlay fa fa-times"></i>
        <form id="add_post_category_form">
            <input type="text" placeholder="Category Name..." id="new_post_category_name"/>
            <select id="new_post_category_access">
                <?php $posts->display_access_categories(); ?>
            </select>
            <input type="submit" class="button" value="Submit"/>
        </form>
    </div>
    
    

</div>


<div class='container row' id="posts_page">
    <h2>POST PREVIEW</h2>
    <div class="row post" >
            <h2 class="section_title"><?php if($post_id!=0){echo $title." - ".$date_f;}?></h2>
            
                <div id='type_youtube' class='layout_panel' style='display:<?php if($post_id!=0 || $m_type === 'Youtube'){ echo "inline-block";} else {echo "none";}?>'>
                    <div class="col-md-6" >
                        <div class="videoWrapper"><iframe src='<?php if($post_id!=0){echo $m_url;}else{echo "https://www.youtube.com/embed/C0DPdy98e4c";} ?>' frameborder="0" allowfullscreen></iframe></div>
                    </div>
                    <div class="col-md-6"><p class='preview_body'><?php if($post_id===0){echo "Content..."; }else{ echo $body;}?></p></div>
                </div>
            
            
                <div id='type_image' class='layout_panel' style='display:<?php if($post_id!=0 && $m_type === 'Image'){ echo "inline-block";} else {echo "none";}?>'>
                    <div class="col-md-6" >
                        <div class="posts_img"><img src="<?php echo $m_url; ?>"/></div>
                    </div>
                    <div class="col-md-6"><p class='preview_body'><?php if($post_id!=0){echo $body;}?></p></div>
                </div>
            
            
                <div id='type_text' class='layout_panel' style='display:<?php if($post_id!=0 && $m_type === 'None'){ echo "inline-block";} else {echo "none";}?>'>
                    <div class="col-xs-0 col-md-2"></div>
                    <div class="col-xs-11 col-md-8"><p class='preview_body'><?php if($post_id!=0){echo $body;}?></p></div>
                    <div class="col-xs-0 col-md-2"></div>
                </div>
            
            
        </div>
</div>





<script type="text/javascript" src="https://tylercrady.com/js/admin_ajax.js?v=<?php echo time();?>"></script>

<?php include '../php/footer.php'; ?>
