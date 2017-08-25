



$(document).ready(function(){
   
   
    //Initializing jquery datepicker, it's on admin/post and admin/portfolio
    //I'm the only one seeing this and I don't really care about the format, so, yeah
    $(function(){
        $( "#datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function() {
                $(this).change();
            }
        });
    });
    
    
    //Creating/editing new post(s) 
    //Creating/editing new post(s) 
    //Creating/editing new post(s) 
    //Creating/editing new post(s) 
    //Creating/editing new post(s) 
    $("#create_new_post").click(function(event){
        
        //We all know what this does
        event.preventDefault();
        
        //Lets grab our values
        var title   = $("#post_title").val();
        var cat     = $("#post_category").val();
        var body    = $("#post_body").val();
        var m_type  = $("#media_type").val();
        
            //Get the media type, then the appropriate url
            if(m_type === "Youtube"){
                var m_url = $("#media_youtube_url").val();
            }
            else if(m_type === "Image"){
                var m_url = $("#media_image_upload").data("image_path");
            }
            else if(m_type === "none"){
                var m_url = "";
            }
        
        var date    = $("#datepicker").val();
        var post_id = $("#post_id").val();
        
        
        
        //Even though I should be the only one inputting anything, justin case.
        body  = body.replace("<script>", "");
        body  = body.replace("</script>", "");
        title = title.replace("<script>", "");
        title = title.replace("</script>", "");
        m_url = m_url.replace("<script>", "");
        m_url = m_url.replace("</script>", "");
        
        //So it is easy to manage on the other side
        var data = {                
            title   : title,
            cat     : cat,
            body    : body,
            m_type  : m_type,
            m_url   : m_url,
            date    : date,
            post_id : post_id
    
        };
        
        if(post_id == 0){//If id is 0 we are creating a new post
            $.ajax({
                type: 'POST',
                url: 'https://tylercrady.com/php/ajax.php',
                dataType: 'json',
                data: ({
                    request: "create_post",
                    data: data
                }),
                success: function(response){ 
                    if(response.status === "success"){
                        show_message("normal", "Post Created");
                        setTimeout(function(){
                            window.location.href = "https://tylercrady.com/admin/post/"+response.new_post_id;
                        }, 2000);
                        
                    }
                    else{

                    }
                }//end ajax success
            });//end ajax
        }//end if
        else{// if id is not 0, it's already a post, lets just update it
            $.ajax({
                type: 'POST',
                url: 'https://tylercrady.com/php/ajax.php',
                dataType: 'json',
                data: ({
                    request: "edit_post",
                    data: data
                }),
                success: function(response){ 
                    if(response === "success"){
                        show_message("normal", "Post Saved");
                    }
                    else{
                        show_message("error", "Error Saving Post");
                    }
                }
            });//end ajax
        }//end else
    });//end creat new post
    
    
    
    //Creating/editing new portolio items
    //Creating/editing new portolio items
    //Creating/editing new portolio items
    //Creating/editing new portolio items
    //Creating/editing new portolio items
    $("#create_new_portfolio").click(function(event){
       
        event.preventDefault();
        
        //Lets grab all our values - we will grab images in just a sec
        var title        = $("#portfolio_title").val();
        var project_type = $("#project_type").val();
        var body         = $("#portfolio_body").val();
        var date         = $("#datepicker").val();
        var technology   = $("#technologies").val();
        var portfolio_id = $("#portfolio_id").val();
        var access       = $("#portfolio_access").val();
        
        body  = body.replace("<script>", "");
        body  = body.replace("</script>", "");
        title = title.replace("<script>", "");
        title = title.replace("</script>", "");
        
        
        //Grab all the image paths, descriptions, id, and current place. 
        //Soon I will be able to swap the image order around somehow.
        var images = new Array();
        $(".image_container").each(function(){
            var description = $(this).children("div").children(".description").val();
            var image_path  = $(this).children("div").children(".portfolio_file").attr("data-image_path");
            var id          = $(this).children("div").children(".portfolio_file").attr("data-id");
            var image_num   = $(this).children("div").children(".portfolio_file").attr("data-image_number");
            
            //I went the array route just for ease. Merging objects was getting weird
            //Soooooo, on the other side just have to remember the position in the array for each value. 
            var temp = new Array(description, image_path, image_num, id);
            images.push(temp);
        });
        
        //At least this will be nice on the other side
        var data = {                
            title        : title,
            technology   : technology,
            project_type : project_type,
            body         : body,
            date         : date,
            portfolio_id : portfolio_id,
            images       : images,
            access       : access
        };
        
        if(portfolio_id == 0){//if id is 0, we are creating a new portfoli item
            $.ajax({
                type: 'POST',
                url: 'https://tylercrady.com/php/ajax.php',
                dataType: 'json',
                data: ({
                    request: "create_portfolio",
                    data: data
                }),
                success: function(response){ 
                    if(response.status === "success"){
                        show_message("normal", "Portfolio Created");
                        setTimeout(function(){
                            window.location.href = "https://tylercrady.com/admin/portfolio/"+response.new_portfolio_id;
                        }, 2000);
                        
                    }
                    else{

                    }
                }//end success
            });//end ajax
        }//end if
        else{// if the id is not 0, we are editing a portfolio item
            $.ajax({
                type: 'POST',
                url: 'https://tylercrady.com/php/ajax.php',
                dataType: 'json',
                data: ({
                    request: "edit_portfolio",
                    data: data
                }),
                success: function(response){ 
                    if(response.status === "success"){
                        show_message("normal", "Portfolio Saved");
                    }
                    else{
                        show_message("error", "Error Saving Portfolio");
                    }
                }//end success
            });//end ajax
        }//end else
    });//end creat new post
    
    
    
    //Uploading an image from the portfolio admin page
    //Uploading an image from the portfolio admin page
    //Uploading an image from the portfolio admin page
    //Uploading an image from the portfolio admin page
    //Uploading an image from the portfolio admin page
    $(document).on("change", ".portfolio_file", function(event){
        
        //typical...
        event.preventDefault();
        
        var curr_image  = $(this).attr("data-image_number");
        var $this       = $(this);
        
        if(typeof $('input[type=file]')[curr_image-1].files[0] !== 'undefined'){
           
            //Show loading
            $("#loading"+curr_image).show();
            
            //Create formData var and append data
            var formData = new FormData();
            formData.append('image', $('input[type=file]')[curr_image-1].files[0]);
            formData.append("image_number", curr_image);
            
            // make ajax call to upload the photo, 
            $.ajax({
                url: "https://tylercrady.com/admin/upload_portfolio_images.php",
                type: "POST",
                data: formData,
                contentType: false,
                      cache: false,
                processData: false,
                success: function(data)
                {
                    //all these checks came from another script, might as well leave 'em in.
                    //I'm running a photoshop action on portfolio images anyways
                    if(data==='invalid file'){
                        $("#loading"+curr_image).hide();
                        show_message("error", "There was an error when uploading that image.");
                    }
                    else if(data === "too small"){
                        $("#loading"+curr_image).hide();
                        show_message("error", "The image you are trying to upload is too small.");
                    }
                    else if(data === "too big"){
                        $("#loading"+curr_image).hide();
                        show_message("error", "The image you are trying to upload is too big.");
                    }
                    else{
                        $this.attr("data-image_path", data).css("background-image","url('"+data+"')");
                        $("#loading"+curr_image).hide();
                        $("#success"+curr_image).show();
                    }
                }
            });
        }
    });//end image upload
    
    
    
    
    
    
    //Change the status of a post, active/inactive
    //Change the status of a post, active/inactive
    //Change the status of a post, active/inactive
    //Change the status of a post, active/inactive
    //Change the status of a post, active/inactive
    $(".change_post_status").click(function(){
       var status = $(this).data('status');
       var id     = $(this).data('id');
       var data = {
           status : status,
           id     : id
       };
       var $this = $(this);//so I can access this in the ajax callback
       $.ajax({
            type: 'POST',
            url: 'https://tylercrady.com/php/ajax.php',
            dataType: 'json',
            data: ({
                request: "change_post_status",
                data: data
            }),
            success: function(response){ 
                if(response.status === "success"){
                    $this.data("status", response.new_status);
                    $this.text(response.status_text);
                    if(response.status_text == "active"){
                        $this.addClass("active_post");
                        $this.removeClass("inactive_post");
                    }
                    else{
                        $this.removeClass("active_post");
                        $this.addClass("inactive_post");
                    }
                }
                else{
                    show_message("error", "Error changing status");
                }
            }
        });//end ajax
        
    });
    
    
    //Change the status of a portfolio, active/inactive
    //Change the status of a portfolio, active/inactive
    //Change the status of a portfolio, active/inactive
    //Change the status of a portfolio, active/inactive
    //Change the status of a portfolio, active/inactive
    $(".change_portfolio_status").click(function(){
       var status = $(this).data('status');
       var id     = $(this).data('id');
       var data = {
           status : status,
           id     : id
       };
       var $this = $(this);//so I can access this in the ajax callback
       $.ajax({
            type: 'POST',
            url: 'https://tylercrady.com/php/ajax.php',
            dataType: 'json',
            data: ({
                request: "change_portfolio_status",
                data: data
            }),
            success: function(response){ 
                if(response.status === "success"){
                    $this.data("status", response.new_status);
                    $this.text(response.status_text);
                    if(response.status_text == "active"){
                        $this.addClass("active_post");
                        $this.removeClass("inactive_post");
                    }
                    else{
                        $this.removeClass("active_post");
                        $this.addClass("inactive_post");
                    }
                    $this.text(response.status_text);
                }
                else{
                    show_message("error", "Error changing status");
                }
            }
        });//end ajax
        
    });
    
    
    //This is the function to show messages to the user --------------------------
    //This is the function to show messages to the user --------------------------
    //This is the function to show messages to the user --------------------------
    //This is the function to show messages to the user --------------------------
    //This is the function to show messages to the user --------------------------
    var timer;
    function show_message(type, message){
        clearTimeout(timer);
        var bg_color;
        if(type === 'error'){
            bg_color = "#4d0000";
        }else{
            bg_color = "#14272E";
        }
        $("#message p").text(message);
        $("#message").css("background-color",bg_color).fadeIn();
        timer = setTimeout(function(){
            $("#message").fadeOut();
        }, 5000);
    }//end function
    
    
    
    
    
    
    
    //Adding an image group to the portfolio admin page
    //Adding an image group to the portfolio admin page
    //Adding an image group to the portfolio admin page
    //Adding an image group to the portfolio admin page
    //Adding an image group to the portfolio admin page
    var image_count = $(".image_container").size();//starts at 1 ( not 0 )if there are no images yet
    $("#add_image").click(function(){
        image_count++;
        var image_div  = '<div class="image_container">';
            image_div += '<div class="col-xs-12 col-sm-6"><p class="input_label">Image Description '+image_count+'</p><textarea class="description"></textarea></div>';
            image_div += "<div class='col-xs-12 col-sm-6 file_box'><p class='input_label'>Image "+image_count+"</p><input data-id='0' data-image_number='"+image_count+"' data-image_path='' type='file' class='portfolio_file'>";
            image_div += "<image class='loading portfolio_loading' id='loading"+image_count+"' src='https://tylercrady.com/images/loading.gif'/>";
            image_div += "<image class='loading portfolio_loading' id='success"+image_count+"' src='https://tylercrady.com/images/upload_success.png'/>";
            image_div += "</div>";
            image_div += "</div>";
        $("#portfolio_images").append(image_div);
    });
    
    
    
    
    
    //Setting all the categories 
    //Setting all the categories 
    //Setting all the categories 
    //Setting all the categories 
    //Setting all the categories 
    //Setting all the categories 
    
    function display_categories(new_category){
        $.ajax({
            type: 'POST',
            url: 'https://tylercrady.com/php/ajax.php',
            data: ({
                request: "display_categories"
            }),
            success: function(response){
                $("#post_category").find("option").remove();
                $("#post_category").append(response);
                $("#post_category").append("<option value='new_category'>New Category</option>");
                $("#post_category").val(new_category);
            }
        });//end ajax
    }//end function
    
    
    
    
    
    
    //Adding a new post category 
    //Adding a new post category 
    //Adding a new post category 
    //Adding a new post category 
    //Adding a new post category 
    $("#post_category").change(function(){
        if($(this).val() === "new_category"){
            $("#add_post_category").fadeIn("fast"); 
        }
    });
    $(".close_overlay").click(function(){
        $(this).parent().hide();
    });
    
    
    $("#add_post_category_form").submit(function(event){
        event.preventDefault();
        
        var name    = $("#new_post_category_name").val();
        var access  = $("#new_post_category_access").val();
        var data = {name: name, access: access};
        
        $.ajax({
            type: 'POST',
            url: 'https://tylercrady.com/php/ajax.php',
            dataType: 'json',
            data: ({
                request: "new_post_category",
                data   : data
            }),
            success: function(response){
                if(response.status === "success"){
                    show_message("normal", "Category added.");
                    $(".overlay").hide();
                    $("#new_post_category_name").val("");
                    display_categories(response.category);//sets all the categories in the dropdown list
                    
                }
            }
        });//end ajax
    });//end form submit
    
    
    
    //Post Media section 
    //Post Media section 
    //Post Media section 
    //Post Media section 
    //Post Media section 
    //Post Media section 
    
    $("#media_type").change(function(){
        $(".post_media").hide();
        var type = $(this).val();
        if(type === "Youtube"){
            $("#media_youtube").fadeIn("fast");
        }
        else if(type === "Image"){
            $("#media_image").fadeIn("fast");
        }
        else if(type === "Video"){
            $("#media_video").fadeIn("fast");
        }
        else{
            $("#media_none").show();
        }
        
    });
    
    
    
    
    
    
    //Uploading an image from the post admin page
    //Uploading an image from the post admin page
    //Uploading an image from the post admin page
    //Uploading an image from the post admin page
    //Uploading an image from the post admin page
    $(document).on("change", "#media_image_upload", function(event){
        
        //typical...
        event.preventDefault();
        var $this       = $("#media_image_upload");
        if(typeof $this[0].files[0] !== 'undefined'){
       
            //Show loading
            $("#image_loading").show();
            
            //Create formData var and append data
            var formData = new FormData();
            formData.append('image', $this[0].files[0]);
     
            
            // make ajax call to upload the photo, 
            $.ajax({
                url: "https://tylercrady.com/admin/upload_post_images.php",
                type: "POST",
                data: formData,
                contentType: false,
                      cache: false,
                processData: false,
                success: function(data)
                {
                    //all these checks came from another script, might as well leave 'em in.
                    //I'm running a photoshop action on portfolio images anyways
                    if(data==='invalid file'){
                        $("#image_loading").hide();
                        show_message("error", "There was an error when uploading that image.");
                    }
                    else if(data === "too small"){
                        $("#image_loading").hide();
                        show_message("error", "The image you are trying to upload is too small.");
                    }
                    else if(data === "too big"){
                        $("#image_loading").hide();
                        show_message("error", "The image you are trying to upload is too big.");
                    }
                    else{
                        $this.attr("data-image_path", data).css("background-image","url('"+data+"')");
                        $("#image_loading").hide();
                        $("#image_success").show();
                    }
                }
            });
        }
    });//end image upload
    
    
    
    
    
    
    
    
    
    
    //LIVE POST PREVIEW --------------------------------------
    //LIVE POST PREVIEW --------------------------------------
    //LIVE POST PREVIEW --------------------------------------
    //LIVE POST PREVIEW --------------------------------------
    //LIVE POST PREVIEW --------------------------------------
    
    
    //Updating the title
    $(document).on("input","#post_title", function(){
        var curr_date = $("#datepicker").val();
        var date_arr  = curr_date.split("-");
        var date      = date_arr[1]+"/"+date_arr[2]+"/"+date_arr[0];
        $("#posts_page .section_title").text($("#post_title").val()+" - "+date);
    });
    
    //Updating the date
    $(document).on("change","#datepicker", function(){
        var curr_date = $("#datepicker").val();
        var date_arr  = curr_date.split("-");
        var date      = date_arr[1]+"/"+date_arr[2]+"/"+date_arr[0];
        $("#posts_page .section_title").text($("#post_title").val()+" - "+date);
    }); 
      
    
    //Updating the body
    $("#post_body").on("input",function(){
        $("#posts_page .preview_body").html($(this).val());
    });
    
    
    //Swapping out the post layout
    $("#media_type").change(function(){
        var type = $(this).val();
        $(".layout_panel").hide();
        if(type === "Youtube"){
            $("#type_youtube").show();
        }
        else if(type === "Image"){
            $("#type_image").show();
        }
        else if(type === "none"){
            $("#type_text").show();
        }
    });
    
    //Changin the youtube video
    $("#media_youtube_url").on("input", function(){
       $(".videoWrapper iframe").attr("src", $(this).val()); 
    });
    
    //Changing the image uploaded
    $(document).on("change","#media_image_upload", function(){
        setTimeout(function(){
            console.log("HERE");
            $("#type_image  .posts_img img").attr("src", $("#media_image_upload").data("image_path")); 
       },400);
    });
    
    
});//End jquery document ready


