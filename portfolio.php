<?php




session_start();
include 'php/classes.php';

//If accessing a forbidden page, after successful login, the user is sent to the page they had previously been trying to access
$_SESSION['requested_url'] = "https://tylercrady.com".$_SERVER['REQUEST_URI'];

//Create our portfolio object, we'll be needing this
$portfolio_object = new portfolio();//create our images objecte

//Checking permissions for this portfolio, if one is being requested, else this whole chunk is disregarded
if(isset($_GET['portfolio']) ){
    
        if($portfolio_object->is_portfolio($_GET['portfolio'])){
            $portfolio_info = $portfolio_object->get_portfolio($_GET['portfolio']);// set the album name
            $access = $portfolio_info['access'];
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
}//end permissions check





include 'php/header.php';



//If there is an portfolio to show, lets show it
    if(isset($_GET['portfolio']) && $portfolio_object->is_portfolio($_GET['portfolio'])){
        
        $current_portfolio = $portfolio_object->get_portfolio($_GET['portfolio']);//retreives the title and description for this album
            
        $title          = $current_portfolio['title'];
        $body           = $current_portfolio['body'];
        $technology     = $current_portfolio['technology'];
        $project_type   = $current_portfolio['project_type'];
        $date           = date('M Y', strtotime($current_portfolio['date']));
        if(array_key_exists('images', $current_portfolio)){
            $portfolio_images         = $current_portfolio['images'];
        }
        
        
        ?>

<!--        <div class="container">
            
        </div>-->

        <div class="container" id='portfolio_page'>

            <div class="row">
                <!-- THIS IS THE mobile META DATA -->
                    <h2 class="section_title mobile_portfolio_title">
                        <?php echo $title; ?>
                        <span class="portfolio_date">
                            <?php echo $date; ?>
                        </span>
                        <span class="portfolio_client">
                                <?php echo $project_type?>
                        </span>
                        
                        <span class="portfolio_technologies">
                            <?php echo $technology;?>
                        </span>

                    </h2>
                
                <!-- This is the entire html for the portfolio images -->
                <!-- This is the entire html for the portfolio images -->
                <div class="col-md-8 column portfolio_slideshow ">
                    <?php $portfolio_object->display_portfolio_slider2($_GET['portfolio']);?>
                </div>
                <!-- This is the entire html for the portfolio images -->
                <!-- This is the entire html for the portfolio images -->
                
                <!-- THIS IS THE DESKTOP META DATA -->
                    <div class="col-md-4 column portfolio_summary ">
                        
                        <h2 class="section_title desktop_portfolio_title">
                            
                            <?php echo $title; ?>
                            <span class="portfolio_date">
                                <?php echo $date; ?>
                            </span>
                            <span class="portfolio_client">
                                <?php echo $project_type;?>
                            </span>
                            
                            <span class="portfolio_technologies">
                                <?php echo $technology;?>
                            </span>
                        </h2>
                        <p><?php echo $body; ?></p>

                    </div>
            </div>

        </div>

<?php  }
        else{ ?><!--If there is no portfolio to show, show the portfolio previews content-->
            
        <div class='container row' id="portfolio_page"><!-- this is the main container div -->
            <?php if( isset($_GET['portfolio']) && !$portfolio_object->is_portfolio($_GET['portfolio'])){ ?>
                <h2 class='section_title'>Couldn't find that portfolio, but here are some others</h2>
            <?php }else{ ?>
                <h2 class='section_title'>Developement Portfolio</h2>
            <?php } ?>
        <!-- Now we will loop through the portfolio items now, grab the info of each one and display it. -->
        <?php 
        
            $portfolio_items = $portfolio_object->get_portfolio_links();
            foreach($portfolio_items as $portfolio){
            
            
            //Lets grab the info we will want to display
            $oktoshow    = false;
            $title       = $portfolio['title'];
            $link        = $portfolio['link'];
            $date        = $portfolio['date'];
            $preview_img = $portfolio['preview_image'];
            $type        = $portfolio['type'];
            $access      = $portfolio['access'];
            
            if($access == "open"){
                $oktoshow = true;
            }
            else if(isset($_SESSION['access']) && ($_SESSION['access'] == $access || $_SESSION['access'] == 5) ){
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
                            <p><?php echo $type;?></p>
                            <p><?php echo $date;?></p>
                         </div>
                     </div>
                 </div>
             </a>
         




            
        <?php  } }  ?><!-- end oktoshow check and for each loop -->
        </div><!-- end our main container div -->
        <?php  }  ?><!-- End else statement -->
       



       


    




















<?php include 'php/footer.php';



