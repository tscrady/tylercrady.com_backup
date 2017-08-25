<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

session_start();
include 'php/classes.php';

$_SESSION['requested_url'] = "https://tylercrady.com".$_SERVER['REQUEST_URI'];

include 'php/header.php';//This has everything we will need for all pages
?>



<div class="container" style='text-align:center'>
    <h2 style="padding:5%">That page was not found.</h2>
</div>




<?php include 'php/footer.php'; ?>
