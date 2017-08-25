<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_STRICT);

include '../image_class.php';
include '../header.php';

if(isset($_GET['album'])){
    $album = $_GET['album'];
    
    $images = new images();
    $images->album_name = $album;
    $images->get_album_images();
    
}
else{
    header("Location: https://tylercrady.com/tylercrady/albums.php");
}



?>



<div class="container">
<?php 
    $images->display_album_images();
?>
</div>

<?php include '../footer.php'; ?>



