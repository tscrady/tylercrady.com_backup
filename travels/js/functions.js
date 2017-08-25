



$(document).ready(function(){
   
        $("#upload_image").click(function(){
            $("#fileToUpload").click();
        });

        
        
        $("#fileToUpload").change(function(event){
            event.preventDefault();
         
            //First check to make sure they actully picked a file, otherwise, they clicked 'cancel' or 'X' on the upload image dialog
            if(typeof $('input[type=file]')[0].files[0] !== 'undefined'){

                var formData = new FormData();
                formData.append('image', $('input[type=file]')[0].files[0]);
                formData.append('request', "image_upload");


                // 3. make ajax call to upload the photo, 
                    $.ajax({
                        url: "php/ajax.php",
                        type: "POST",
                        dataType: "json",
                        data:  formData,
                        contentType: false,
                              cache: false,
                        processData: false,
                        success: function(data)
                        {
                            console.log(data);
                            if(data !== "error"){
                                $("#upload_image").text("SUCCESS!");
//                                $("#image_preview").attr("src", data[0]).fadeIn();
                                $("#image_preview").fadeIn();
                                $("#image_preview").attr("data-path-to-file", data[1]);
                                
                            }
                            else{
                                $("#upload_image").text("ERROR.");
                            }
                        }
                    });
            }
        });
        
   

    //User has clicked 'Get my location'
    $("#enable_location").click(function(){
        getLocation();
    });
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, deniedCallback);
        } 
    }
    function showPosition(position) {
        var lat  = parseFloat(position.coords.latitude).toFixed(6);
        var long = parseFloat(position.coords.longitude).toFixed(6);
        $("#user_lat").val(lat); 
        $("#user_long").val(long);
        $("#user_position").fadeIn();
        
        //Send the ajax data to php to record our location
        set_location();
        
    }//end show position
    
    function deniedCallback(){
        alert("It's not going to work if you don't give location permission");
            //Record that location permission was denied
            var location_record = {
                    "location_granted"  : false
            };
            recordLocation(location_record);
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




