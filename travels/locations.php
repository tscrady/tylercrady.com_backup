<?php

include 'php/header.php';
?>


      
    <img src='images/loading.gif' id='loading'/>
   
    <div  class="stat">
        <input type="checkbox" checked id="show_tyler"/>
        <p class="traveler">Tyler<p> 
        <p id="Tyler"></p>
        <img src='images/green_pin.png'/>
    </div>
<!--    <div  class="stat">
        <input type="checkbox" checked id="show_sarah"/>
        <p class="traveler">Sarah</p> 
        <p id="Sarah"></p>
        <img src='images/pink_pin.png'/>
    </div>-->
<!--    <div  class="stat">
        <input type="checkbox" checked id="show_ed"/>
        <p class="traveler">Ed</p> 
        <p id="Ed"></p>
        <img src='images/blue_pin.png'/>
    </div>-->
<!--    <div  class="stat">
        <input type="checkbox" checked id="show_hua"/>
        <p class="traveler">Hua</p> 
        <p id="Hua"></p>
        <img src='images/orange_pin.png'/>
    </div>-->
    
 <div id="map"></div>
    
    
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBn0yZCZ6OTAY4rusydzQHFECsWAvNXqsQ&callback=initMap">
    </script>
