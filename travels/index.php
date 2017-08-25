<?php 
include 'php/header.php';
?>



    <div class="container">
    
        <div class="field">
            <p class="field_label">Who are you? Click to change</p>
            <select id="user_name">
                <option value="Tyler">Tyler</option>
                <option value="Sarah">Sarah</option>
                <!--<option value="Hua">Hua</option>-->
                <!--<option value="Ed">Ed</option>-->
            </select>
        </div>

        <div class="field">
            <p class="field_label">Description</p>
            <textarea id='user_text'></textarea>
        </div>
        
        <div class="field">
            <p class="field_label">Click to upload an image.</p>
            <input  hidden type="file" accept="image/*" name="fileToUpload" id="fileToUpload">
            <a href="#" id="upload_image" class="button">Upload Image</a>
            <img id="image_preview" data-path-to-file="" src="../images/check.png" />
        </div>
        
        <div class="field">
            <p class="field_label">Where are you? Click to pin</p>
            <p  id="enable_location" class="button">Pin Location</p>
        </div>
        
        <div class="field" id="user_position">
            <p class="field_label">This is your position</p>
            <input type="text"   id="user_lat"/>
            <input type="text"   id="user_long"/>
            <input type="text"   id="user_address"/>
        </div>
    
    </div>


<?php
include 'php/footer.php';
?>