<?php
        $target_dir = "images/";

        //Name the file after the recipe ID.
        $target_file = $target_dir.$id.".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);

        $upload = true;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES['file']['tmp_name']);
            if($check !== false) {
                //File is an image
                $upload = true;
            } else {
                //File is not an image.
                $upload = false;
            }
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $upload = false;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($upload == true)  {
            copy ($_FILES['file']['tmp_name'], $target_dir . $_FILES['file']['name']) 
    or die ("Could not copy file"); 
        }
?>