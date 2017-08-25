<?php

?>

<!DOCTYPE html>
    
    <html>
        <head>
            
            <title>Our Travels</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta charset="utf-8">

                
            <!-- css -->
                <!--<link type="text/css" href="css/styles.less?v=<?php echo time();?>" rel="stylesheet/less" />-->
                <link type="text/css" href="css/vanilla.css" rel="stylesheet" />
            <!-- css -->
            
            <!-- js -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                <script type="text/javascript" src="js/functions.js?v=<?php echo time();?>"></script>
                <script type="text/javascript" src="js/ajax.js?v=<?php echo time();?>"></script>
                <script type="text/javascript" src="js/less.js"></script>
            <!-- js -->
            
        </head>
    <body>
    <div id="wrapper">
        
        <div id="nav">
            <?php 
                $url =  "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                
                //First link
                if($url === "https://tylercrady.com/travels/index.php" ) {
                    echo '<a class="button nav active" href="/travels/index.php">Create Pin</a>';
                }else{
                    echo '<a class="button nav " href="/travels/index.php">Create Pin</a>';
                } 
                //Second link
                if($url === "https://tylercrady.com/travels/locations.php"  || $url === "https://tylercrady.com/travels/") {
                    echo '<a class="button nav active" href="/travels/locations.php">View Pins</a>';
                }else{
                    echo '<a class="button nav" href="/travels/locations.php">View Pins</a>';
                }
                
                
                ?>  
            
            
            
        </div>

