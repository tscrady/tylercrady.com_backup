    
    //When we get the data back, we assign them to these objects so
    //that when we want to hide/show the markers we have them stores client side
    var MARKERS = {
        Tyler: [],
        Ed: [],
        Hua: [],
        Sarah: []
    };
    var map;//Our GOOGLE MAP OBJECT
    var center = {lat: 34.5133, lng: -94.1629};//center of the world
        
    
    //Hiding or showing pins
    $(document).ready(function(){
        $("input[type='checkbox'").change(function(){
            var curr = $(this);
            var checked = curr.is(":checked");
            var to_toggle = curr.next(".traveler").text();
            var themap;
            if(!checked){
                themap = null;
            }
            else{
                themap = map;
            }
            for (var i = 0; i < Object.keys(MARKERS[to_toggle]).length; i++) {
                MARKERS[to_toggle][i].setMap(themap);
            }
        });//End change function
        
        
        

        
    });//End document ready function
    
    
    
    


    
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 3,
          center: center
        });
       
        get_pins();
        
    }

    



    function get_pins(){
        //This will hold all the people that we have available for selection
        var travelers = Array();
        //Get all checked names to send to ajax
        $(".stat").each(function(){
                travelers.push($(this).children(".traveler").text());
        });
        
            $.ajax({
                type: 'POST',
                url: 'php/ajax.php',
                dataType: 'json',
                data: ({
                     request: 'get_pins',
                     travelers: travelers
                }),
                success: function(response) {
                    console.log(response);
                    //Actually plot the points on the map
                    plot_points(response);
                } 
            });
    }//End get_points
   
   //THIS IS WHERE THE MAGIC HAPPENS
   function plot_points(points){
       
       
       
        for (var traveler in points) {
            $("#"+traveler).text(points[traveler].length);
            for(var j = 0; j < points[traveler].length; j++ ){
                var curr_lat  = points[traveler][j].latitude;
                var curr_long = points[traveler][j].longitude;
                var curr_addr = points[traveler][j].address;
                var curr_date = points[traveler][j].date;
                var curr_time = points[traveler][j].time;
                var curr_text = points[traveler][j].text;
                var curr_imag = points[traveler][j].image;
                var title     = traveler+" was here.";
                
                //Checking to see which color pin we want to assign for this traveler
                if(traveler === "Tyler"){
                    var icon = 'images/green_pin.png';
                }
                else if(traveler === "Sarah"){
                    var icon = 'images/pink_pin.png';
                }
                else if(traveler === "Ed"){
                    var icon = 'images/blue_pin.png';
                }
                else if(traveler === "Hua"){
                    var icon = 'images/orange_pin.png';
                }
                
                //console.log(parseFloat(curr_lat)+" - "+parseFloat(curr_long)+" - "+typeof(parseFloat(curr_long)));
                var marker = new google.maps.Marker({
                    position: {lat: parseFloat(curr_lat), lng: parseFloat(curr_long)},
                    map: map,
                    icon: icon,
                    title: title
//                    label: title
                });
                
                
                //Create the content for our info window
                var content = "<div class='window_info'>";   
                    content+= "<p class='info_window_date'>"+curr_date+"</p>";   
                    content+= "<p class='info_window_time'>"+curr_time+"</p>";   
                    content+= "<p class='info_window_addr'>"+curr_addr+"</p>";   
                    content+= "<p class='info_window_text'>"+curr_text+"</p>";   
                    content+= "<img class='info_window_imag' src='"+curr_imag+"' />";   
                    content+= "</div>";   
                //New info window
                var infowindow = new google.maps.InfoWindow();
                //Add listener to this one
                google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                    return function() {
                       infowindow.setContent(content);
                       infowindow.open(map,marker);
                    };
                })(marker,content,infowindow)); 
                
                //Checking to see which traveler to add these markers to
                //We will use these objects for showing/hiding the markers later
                if(traveler === "Tyler"){
                    MARKERS.Tyler.push(marker);
                }
                else if(traveler === "Sarah"){
                    MARKERS.Sarah.push(marker);
                }
                else if(traveler === "Ed"){
                    MARKERS.Ed.push(marker);
                }
                else if(traveler === "Hua"){
                    MARKERS.Hua.push(marker);
                }
                
            }//end j loop
        }// end traveler loop
        
        
        

       
       
       $("#loading").hide();
   }//end plot_points