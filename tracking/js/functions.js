



$(document).ready(function(){
   
     
        
   
    var position_object_id;
    //Start Tracking
    $("#enable_location").click(function(){
        getLocation();
    });
    //Stop tracking
    $("#disable_location").click(function(){
        navigator.geolocation.clearWatch(position_object_id);
    });
    
    function getLocation() {
        if (navigator.geolocation) {
            position_object_id = navigator.geolocation.watchPosition(showPosition, errorCallback, {enableHighAccuracy:true, maximumAge:30000, timeout:27000});
//            alert(position_object_id);s
        } 
    }
    
    var count = 1;
    function showPosition(position) {
        var lat   = parseFloat(position.coords.latitude).toFixed(4);
        var long  = parseFloat(position.coords.longitude).toFixed(4);
        var speed = parseFloat(2.2369*position.coords.speed).toFixed(2);
        var results = '<p>'+count+'. Lat: '+lat+', long: '+long+', speed: '+speed+', id: '+position_object_id+'</p>';
        $("#user_position").append(results);
        $('html,body').animate({ scrollTop: $(document).height() }, 200);
        //Send the ajax data to php to record our location
//        set_location();
        
    }//end show position
    
    function errorCallback(){
        alert("It's not going to work if you don't give location permission");
    }
    
    
    
    //User has clicked pin location
    function set_location(){
        
        var lat  = $("#user_lat").val();
        var long = $("#user_long").val();
        var name = $("#user_name").val();
        var text = $("#user_text").val();
        var img  = $("#image_preview").attr("data-path-to-file");
        //We will send this data to record the location info after the ajax call
        var location_info = {
                "latitude"   : lat,
                "longitude"  : long,
                "name"       : name,
                "text"       : text,
                "img"        : img
        };
        
        $.ajax({
            type: 'POST',
            url: 'php/ajax.php',
//            dataType: "json",
            data: ({
                    request : "set_location",
                    location_info: location_info
            }),
            success: function(response) {
//                console.log(response);
                $("#enable_location").text("SUCCESS!");
                $("#user_address").val(response);
                setTimeout(function(){
                     clear_a_form();
                },1000);
            }
        });//end ajax
        
    }//end setlocation
    
    
    
    
    
    

    
    
    

    
    
    //Just reset the page
    function clear_a_form(){
        $("#enable_location").text("Pin Location");
        $("#upload_image").text("Upload Image");
        $("#user_lat").val("");
        $("#user_long").val("");
        $("#user_name").val("Tyler");
        $("#user_text").val("");
        $("#image_preview").attr("src", "").hide();
        $("#image_preview").attr("data-path-to-file", "");
        $("#user_address").val("");
        $("#user_position").hide();
    }
    
    
    
    
    
    

    
    
    

    
    
    //RECORD THE LOCATION ------------------------------------------------------
    //RECORD THE LOCATION ------------------------------------------------------
    //RECORD THE LOCATION ------------------------------------------------------
        function recordLocation(data){
            $.ajax({
                type: 'POST',
                url: 'php/ajax.php',
                dataType: 'json',
                data: ({
                        request      : "record_location",
                        location_data: data
                }),
                success: function() { 
                    

                }
            });//end ajax call
        }//End function
    //RECORD THE LOCATION ------------------------------------------------------
    //RECORD THE LOCATION ------------------------------------------------------
    //RECORD THE LOCATION ------------------------------------------------------
    
    
});






var alerts;
function showError(string){
    clearTimeout(alerts);
    $("#form_error").text(string).show();
    alerts = setTimeout(function(){
        $("#form_error").fadeOut();
    },5000);
}




