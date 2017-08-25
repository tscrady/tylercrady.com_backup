<?php

session_start();
//echo $_SESSION['requested_url'];
include 'php/classes.php';
include 'php/header.php';

?>



<div class="container">
     <div id="login_container">
         <h2 class="section_title">Login</h2>
         <form id="login_form" action="" >
         <input type="text" id="username" placeholder="username">
         <input type="password" id="password" placeholder="password">
         <input type="submit" id="login_submit" >
         </form>
     </div>
</div>



<?php include 'php/footer.php';?>

