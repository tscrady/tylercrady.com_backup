<?php 

session_start();
include 'php/classes.php';
include 'php/header.php';




?>

<img class='preload' src='images/home_slider_images/home_image_biking_bright.jpg' style='position:absolute;top:-1000px;width:0px;'/>
<img class='preload' src='images/home_slider_images/home_image_developer_bright.jpg' style='position:absolute;top:-1000px;width:0px;'/>
<img class='preload' src='images/home_slider_images/home_image_drumming_bright.jpg' style='position:absolute;top:-1000px;width:0px;'/>


<div id="slider_container" class="container">
    <div id='slider'></div>
    <div id='slider_overlay'></div>
        <div id="slider_content">
            <div id='home_name' class=''>
            <h2 class='home_title'>Tyler Crady</h2>
            <p class='home_attribute'>
                <span id='climber' class="slide_label">developer</span>
                <span id='drummer'  class="slide_label">drummer</span>
                <span id='biker'    class="slide_label">Adventurer</span>
            </p>

            </div>
        </div>
</div>


<div class="container row " id='home_page'>
    
    <a href='/portfolio/'>
        <div class="col-md-4  ">
            <div class="blue_container home_page_panel">
                <div class="column_cover">
                    <div class="column_cover_text">
                        <i class='panel_image fa fa-code' ></i>
                        <p class='panel_title'>I write code</p>
                    </div>
                </div>
                <p class='panel_description desktop_copy'>I currently work at a digital marketing firm creating web sites and applications. I've worked on 
                    some big campaigns promoting brands such as U.S. Bank, Mattress Firm, Dish Latino and BudLight.
                    I enjoy creating sites and applications so I sometimes work on fun little projects on my own; this website being one of them.
                    
                </p>
                <p class='panel_description mobile_copy'>
                    I currently work at a digital marketing firm creating web sites and applications. 
                </p>
            </div>
        </div>
    </a>
    
    <!--<a href='/posts/music/' >-->
        <div class="col-md-4 " >
            <div class="blue_container home_page_panel" >
                <div class="column_cover">
                    <div class="column_cover_text">
                        <i class='panel_image fa fa-music' ></i>
                        <p class='panel_title'>I play music</p>
                    </div>
                </div>
                <p class='panel_description desktop_copy'>When not developing, I spend a lot of time playing music. I play drums for a heavy metal band called RYNO and have a 
                youtube channel where I post drum covers of some of my favorite metal bands. I also play the piano. One of my favorite songs to play on the piano is 
                Midnas Lament from Zelda Twilight Princess.
                </p>
                <p class='panel_description mobile_copy'>
                    I play drums for a heavy metal band, <strong>RYNO</strong>; I have a youtube channel, <strong>cradycovers</scrong>; and I play the piano.
                </p>
            </div>
        </div>
    <!--</a>-->
    
    <!--<a href='' >-->
        <div class="col-md-4 " >
            <div class="blue_container home_page_panel">
                <div class="column_cover">
                    <div class="column_cover_text">
                        <i class='panel_image fa fa-bicycle' ></i>
                        <p class='panel_title'>I do lots of stuff</p>
                    </div>
                </div>
                <p class='panel_description desktop_copy'>
                    In my free time I do lots of stuff. I solve some of the Rubiks cubes (2-6 and some other shapes). I bike along trails and run <a href="https://tylercrady.com/images/ToughMudder2016">obstacle
                    courses</a> in the Summer. I like playing video games such as Heroes of the Storm and Halo 5. I also play pool (terribly).
                </p>
                <p class='panel_description mobile_copy'>
                    My hobbies include, rubiks cubes, biking, running <a href="https://tylercrady.com/images/ToughMudder2016">obstacles courses</a>
                    , video games, and pool.
                </p>
            </div>
        </div>
    <!--</a>-->
    
    
</div>





<?php 

include 'php/footer.php';

?>