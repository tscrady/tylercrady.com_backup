<?php
session_start();
include 'php/classes.php';
include 'php/header.php';

?>

<div class="container">

<div class="blue_container contact_container">
    
    
    <form action="" class='contact_form'>
        
        <p>tscrady@gmail.com</p>
        <div class="row row-no-margin">
            <div class="col-xs-4  col-no-padding">
                    <a target='_blank' class='linked_in_color social_button' href="https://www.linkedin.com/in/tyler-crady-1a34b267/">
                        <i class="fa fa-linkedin"></i>
                    </a>
            </div>
            <div class="col-xs-4 col-no-padding">
                    <a target='_blank' class='facebook_color social_button' href="https://www.facebook.com/tyler.crady.7">
                        <i class="fa fa-facebook"></i>
                    </a>
            </div>
            <div class="col-xs-4 col-no-padding">
                <a target='_blank' class='youtube_color social_button' href="https://www.youtube.com/channel/UCkYoiUEvLvDgrfj3pGdhxZQ">
                    <i class="fa fa-youtube"></i>
                </a>
            </div>
        </div>
        
        <!--<input type="email" placeholder="Email..."/>-->
        <textarea placeholder="Message..."></textarea>
        <input type="submit" class="button" value="Send Message"/>
    </form>
    
    
</div><!-- end contact form -->

</div><!-- end container -->













<?php include 'php/footer.php'; ?>

