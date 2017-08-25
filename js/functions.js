/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Some globals 
var slider_index  = 0;
var slider_interval;
var tallest = 0;

$(document).ready(function(){
    
    
    
    $(window).on("load", function(){
        //Run these as soon as the page loads
        scrolling_albums();
        fix_nav_dropdowns();
        setTimeout(function(){// REMOVE THIS ONCE YOU COMPILE LESS
            equal_column_height();
        }, 300);
    });
    
    
    
    
   
    $(window).resize(function(){
        center_album();
        scrolling_albums();
        equal_column_height();
        fix_nav_dropdowns();
    });
    
    $(window).scroll(function(){
       page_top(); //controls whether or not to show the 'page top' button
    });
    
    
    
    //Same height home page columns
    
    function equal_column_height(){
        //Reset to default
        tallest = 0;
        $(".home_page_panel").css("height", "auto");
        
        //Find tallest column
        $(".home_page_panel").each(function(){
           var curr_height = $(this).css("height");
            if(tallest === 0 ){
                tallest = curr_height;
            }
            else if(curr_height > tallest){
                tallest = curr_height;
            }
        });
         
        //Set pannels to tallest column
        $(".home_page_panel").css("height", tallest).css("opacity","1");
    
    }// equal_columns function
    
    
    
    
    
    
    //This adjusts the width of the slider container after the page has loaded all elements and such
    setTimeout(function(){
        //                   120px + 5 for l/r border + 4 for l/r margin
        $("#album_slide").width(($(".album_link_container").width()+18) * $(".album_link_container").size() );
        $(".thumbnail_slider").width(($(".portfolio_thumb").width()+10) * $(".portfolio_thumb").size() );
        $("#album_container").css("opacity","1");
        center_album();
    },500);
    //then, it slides the current album into view, if it's out of view.
    
    function center_album(){
        
            $("#album_container").stop();
            var album_index = $(".active_album").index();
            var left        = (album_index * 128);
            var offset      = ($("#album_container").width() - 120)/2;
            $("#album_container").animate({scrollLeft:left-offset}, "fast");
        
    }
    
    function scrolling_albums(){
        
        var $width = $(window).width();
        var $slider_width = $("#album_slide").width();
        var $thumbnail_slider_width = $(".thumbnail_slider").width();
        
        //Checking the images slider
        if($width < $slider_width){
            $("#album_container").css("overflow-x", "scroll");
        }
        else{
            $("#album_container").css("overflow-x", "auto");
        }
        
        //Checking the portfolio thumbnail slider
        if($width < $thumbnail_slider_width){
            $(".thumbnail_container").css("overflow-x", "scroll");
        }
        else{
            $(".thumbnail_container").css("overflow-x", "hidden");
        }
        
        
        
        
    }//end function
    
    
    //PAGE TOP FUNCTION ----------------------------------------------------
    function page_top(){
        var $top = $(document).scrollTop();
        if($top > 100){
            $("#page_top").show();
        }
        else{
            $("#page_top").hide();
        }
    }//end function
    $("#page_top").click(function(){
        $('html,body').animate({scrollTop:"0px"}, 200);
    });
    
    
    
    //loggin a user in ----------------------------------------------------------------
    $("#login_form").submit(function(event){
        event.preventDefault();
        var username = $("#username").val();
        var password = $("#password").val();
        var regex    = /[a-zA-Z0-9\s]/ ;
        
        if(username === "" || !regex.test(username)){
            show_message("error", "The username is incorrect.");
        }
        else if(password != '' && !regex.test(password)){
            show_message("error", "The password is incorrect.");
        }
        else{
            $.ajax({
                type: 'POST',
                url: 'php/login_user.php',
                dataType: 'json',
                data: ({
                    username: username,
                    password: password
                }),
                success: function(response){ 
                    
                    if(response.status === "success"){
                        show_message("normal",response.message);
                        console.log(response.url);
                        window.location.href = response.url;
                    }
                    else{
                        show_message("error",response.message);
                    }
                }
            });//end ajax
        }
        return false;
    });//end login_submit
    
    
    
    
    
    //Logging a user out - probably will never be used but whatevs
    $("#logout").click(function(event){
       event.preventDefault();
       $.ajax({
           
         type:"POST",
         url: 'https://tylercrady.com/php/login_user.php',
         dataType:"json",
         data:({
             logout: "logout"
         }),
         success:function(response){
             if(response.status === "success"){
                 window.location.href='https://tylercrady.com/login';
             }
         }
       });
    });//end function
    
    
    
    
    
    //Sending a message via the contact form --------------------------------------------------
    $(".contact_form").submit(function(event){
        event.preventDefault();
        var $form   = $(this);
        var message = $(this).children("textarea").val();
        var regex   = '/[a-zA-Z0-9\s\\.\'\",]+/';
        
        if(message === "" || message === " "){
            show_message("error", "You didn't write anything");
        }
        else if(message.match(regex)){
            show_message("error", "Character not allowed.");
        }
        else{
            $.ajax({

              type:"POST",
              url: 'https://tylercrady.com/php/ajax.php',
              data:({
                  request: "send_message",
                  message: message
              }),
              success:function(response){
                  if(response === "success"){
                      $form.children("input[type='submit']").val("Message sent.");
                      setTimeout(function(){
                          $form.children("textarea").val("");
                          $form.children("input[type='submit']").val("Send Message");
                      }, 3000);
                  }
                  else{
                      show_message("error", "Character not allowed.");
                  }
              }
            });
        }
    });
    
    
    
    
    
    //This is the function to show messages to the user --------------------------
    var timer;
    function show_message(type, message){
        clearTimeout(timer);
        var bg_color;
        if(type === 'error'){
            bg_color = "#4d0000";
        }else{
            bg_color = "#14272E";
        }
        $("#message p").text(message);
        $("#message").css("background-color",bg_color).fadeIn();
        timer = setTimeout(function(){
            $("#message").fadeOut();
        }, 5000);
    }//end function
    
    
    
    
    // HOME PAGE SLIDESHOW ------------------------------------------------------------------------------
    
    var biking      = new Array("home_image_biking_bright.jpg", "developer");
    var gaming      = "home_image_gaming.jpg";
    var cubing      = "home_image_cubing.jpg";
    var drumming    = new Array("home_image_drumming_bright.jpg", "drummer");
    var programming = "home_image_programming.jpg";
    var developer   = new Array("home_image_developer_bright.jpg", "climber");
    var images      = new Array(developer, drumming);
    
    
    
    
    slider_interval = setInterval(function(){
        slideshow(images);
    },12000);
//    slideshow(images);
    
    
    function slideshow(){
        
        slider_index++;
        if(slider_index === images.length){
            slider_index = 0;
        }
        
        var $wwidth = $(window).width();
        var $container = $("#slider");
        

        //Hide it, change the image, fade in
        $container.hide();
        $container.css({"background-image": "url(images/home_slider_images/"+images[slider_index][0]});
        $container.fadeIn(2000);
                    
        
        //Highlight which slide we are on
        $(".home_attribute .slide_label").css("color", "#DFDFE5").css("opacity","0");//set all to white
        $(".home_attribute .slide_label").eq(slider_index).css("color", "#6E8F76").css("opacity","0.9");//set current to green
        
        if($wwidth < 720){
            $(".home_attribute .slide_label").css("opacity", "0");
            $(".home_attribute .slide_label").eq(slider_index).css("opacity","1");
        }
       
    }//end slideshow
    
    
    
//    $(".home_attribute .slide_label").click(function(){
//        //First things, stop our slideshow
//            clearInterval(slider_interval);
//        //Get the position of our clicked element
//            var  position = $(this).index();
//        //Set the index of our slideshow to the corrent new position
//            slider_index = position;
//
//        //Highlight which slide we are on
//            $(".home_attribute .slide_label").css("color", "#DFDFE5");
//            $(this).css("color", "#6E8F76");
//            
//        //Set our slider image to current selection
//            var image_url = "url(/images/home_slider_images/"+images[position][0];
//        //Reset our interval 
//            $("#slider").css("background-image", image_url );
//            slider_interval = setInterval(function(){
//                slideshow();
//            },10000);
//    });//end on click
    
    
    
    
    
  
  
  
  
  
  
  
  
  
    //This dropdown menu fixes -----------------------------
        $(".dropdown-menu").hover(function(){
            if($(window).width() > 1000){
                console.log("something");
                $(this).prev('a').addClass("subnav_open");
            }
        }, function(){
            $(this).prev('a').removeClass("subnav_open");
        });//end toggle


        function fix_nav_dropdowns(){
            if($(window).width() < 1000){
                $(".dropdown_toggle").attr("data-toggle","dropdown");
            }
            else{
                $(".dropdown_toggle").removeAttr("data-toggle");
            }
        }
    //This dropdown menu fixes -----------------------------
      

    
    
    
    
    
    //PORTFOLIO SLIDER --------------------------------------------
    
    setTimeout(function(){
        if($(window).width() > 720){
            var $margin_top = ( $(".portfolio_main_image").height() - $(".portfolio_main_image img").height() ) /2;
            $(".portfolio_main_image img").css("paddingTop", $margin_top);
        }
    },100);
    
    
    $(".portfolio_thumb").click(function(){
        //Remove active from all thumbs
            $(".portfolio_thumb").removeClass("active_portfolio_thumb");
        //Add it to the current thumb
            $(this).addClass("active_portfolio_thumb");
        
        //hide image, change image url,
            $(".portfolio_main_image img").hide().attr("src", $(this).attr("data-src"));
         
        if($(window).width() > 720){
        //MMake sure our padding is 0 to start with
            $(".portfolio_main_image img").css("paddingTop", 0);
        //Our padding to make the image centered vertically
            var $margin_top = ( $(".portfolio_main_image").height() - $(".portfolio_main_image img").height() ) /2;
            $(".portfolio_main_image img").css("paddingTop", $margin_top);
        }
            
        //Hide the description, add new description
            $(".image_description").hide().text($(this).attr("data-description"));
        //Fade both description and image in 
            $(".portfolio_main_image img, .image_description").fadeIn('1000');
    });
    
    
});//end document ready


