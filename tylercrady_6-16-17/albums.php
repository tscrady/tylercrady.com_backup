<?php

include 'php/header.php';
include 'php/image_class.php';

?>



<div class="container">
    <?php
        $images = new images();
        $albums = $images->get_album_names();
        var_dump($albums);
    ?>
</div>










<?php

include 'php/footer.php';

?>
