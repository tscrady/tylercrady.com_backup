



<nav class="navbar navbar-default container">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span style="float:right;margin-top:2px;">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </span>
                <span style="display:inline-block;float:left;margin-right:6px;margin-top:1px;">MENU</span>
            </button>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
            <?php 
                $page = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                if($page == "https://tylercrady.com/"){
                    echo  "<li><a href='/' class='button active_nav ' >Home</a></li>";
                }
                else{
                    echo  "<li><a href='/' class='button ' >Home</a></li>";
                }
                
                
                //-------------- IMAGES LINKS --------------------------------
                    $image_active = "";
                    if(strpos($page, "images") > -1 ){
                        $image_active = "active_nav";
                    }
                    $images = new images();//Create an image object
                    $albums = $images->nav_album_links();// get the links to albums for the nav
                    echo  "<li><a href='/images' id='images_dropdown' class='button $image_active dropdown_toggle' data-open='false'  aria-haspopup='true' >Images<span class='caret'></span></a>"
                            . "<ul class='dropdown-menu'>$albums</ul></li>";
                //-------------- IMAGES LINKS --------------------------------
                
                //---------------- PORTFOLIO LINK -----------------------
                        
                        //Check if the portfolio is active nav
                        $portfolio_active = "";
                        if(strpos($page, "https://tylercrady.com/portfolio") > -1 ){
                            $portfolio_active = "active_nav";
                        }
                        $portfolio_object = new portfolio();
                        $portfolios = $portfolio_object->display_portfolio_navigation();
                        echo  "<li><a href='/portfolio' class='button $portfolio_active dropdown_toggle' data-open='false'  aria-haspopup='true'>Portfolio<span class='caret'></span></a><ul class='dropdown-menu'>$portfolios</ul></li>";
                //---------------- PORTFOLIO LINK -----------------------
             
                 
               //-------------------- POSTS ---------------------------------
                        $posts_active = "";
                        if(strpos($page, "posts") > -1 ){
                            $posts_active = "active_nav";
                        }
                        $posts = new posts();
                        $post_categories = $posts->nav_links();
                        echo  "<li><a href='/posts' class='button $posts_active dropdown_toggle' data-open='false'  aria-haspopup='true'>Blog<span class='caret'></span></a><ul class='dropdown-menu'>$post_categories</ul></li>";
                        
               //-------------------- POSTS ---------------------------------    
                        
                
                        
               
                // ---------------------------- Contact button ----------------------------   
                
                    //Check if the contact page is active nav
                        $contact_active = "";
                        if(strpos($page, "contact") > -1 ){
                            $contact_active = "active_nav";
                        }
                        echo  "<li><a href='/contact' class='button $contact_active'>Connect</a></li>";

                    
                // ---------------------------- Contact button ----------------------------   
                    
                        
                //------------------------ADMIN--------------------------------
                        $admin_active = "";
                        if(strpos($page, "admin") > -1 ){
                            $admin_active = "active_nav";
                        }
                        if(isset($_SESSION['access']) && $_SESSION['access'] == 5){
                        ?>
                        <li>
                            <a href='/admin/all_posts.php' class='button <?php echo $admin_active;?> dropdown_toggle' data-open='false'  aria-haspopup='true'>Admin<span class='caret'></span></a>
                            <ul class='dropdown-menu'>
                                <li><a href="/admin/post.php" class="button"><p class="nav_album_title">New Post</p></a></li>
                                <li><a href="/admin/all_posts.php" class="button"><p class="nav_album_title">All Posts</p></a></li>
                                <li><a href="/admin/portfolio.php" class="button"><p class="nav_album_title">New Portfolio</p></a></li>
                                <li><a href="/admin/all_portfolio.php" class="button"><p class="nav_album_title">All Portfolio</p></a></li>
                            </ul>
                        </li>
                        <?php }
               //------------------------ADMIN--------------------------------        
                        
                        
                // ---------------------------- Log in/Log out button ----------------------------
                    $active_nav = "";
                    if(strpos($page, "login") > -1 ){
                        $active_nav = "active_nav";
                    }
                    if(isset($_SESSION['logged_in']) && isset($_SESSION['access'])){
                        echo "<li class='login_nav_item'><a class='button  $active_nav '  id='logout' href=''>Log Out</a></li>";
                    }
                    else{
                        echo "<li class='login_nav_item'><a class='button  $active_nav '  href='/login'>Log In</a></li>";
                    }
                // ---------------------------- Log in/Log out button ----------------------------     
                    
                    
            ?>
                
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.headernavbar -->
        </div><!-- /.container-fluid -->
</nav>


<!--<div id="nav-anchor"></div>-->
