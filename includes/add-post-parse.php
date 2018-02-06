<?php
//Parse the upload if the user submitted the form
if( $_POST['did_add_post'] ){
  echo '<pre>';
  print_r($_FILES);
  echo '</pre>';

  //configuration: desired image sizes and destination
  $sizes = array(
    'thumbnail' => 150,
    'medium'    => 300,
    'large'     => 500,
  );

  //image directory
  $target_directory = 'uploads/';

  //The file that the user uploaded
  $original_file = $_FILES['uploadedfile']['tmp_name'];


  //validation:
  //make sure it is an image
  list( $width, $height ) = getimagesize($original_file);
  if( $width > 0 AND $height > 0 ){
      //what type of file?
      $filetype = $_FILES['uploadedfile']['type'];
      switch($filetype){
        case 'image/gif':
          //Make a gif
          $src = imagecreatefromgif($original_file);
          break;
        case 'image/pjpeg':
        case 'image/jpg':
        case 'image/jpeg':
        //create jpg
          $src = imagecreatefromjpeg($original_file);
        break;
        case 'image/png':
        //make a png
        //TODO: fix the memory limit.
          $src = imagecreatefrompng($original_file);
        break;

        //not an allowed type
        default:
          $errors['type'] = 'This is not an allowed file type.';
      }//end switch

      //Hashed filename
      $uniquehash = sha1(microtime());
      //uploads/jriakgkrie_medium.jpg

      //resize the image using the sizes array
      foreach ( $sizes as $size_name => $pixels ){
        /*
          SQUARE CROP CALCULATIONS
        */
        if( $width > $height ){
          //lnadscape original image
          $crop_x = ($width - $height) / 2;
          $crop_y = 0;
          $shortest_side = $height;
        }else{
          //portrait original image
          $crop_x = 0;
          $crop_y = ($height - $width) / 2;
          $shortest_side = $width;
        }//end if else
        //create a blank canvas of the desired size
        $tmp_canvas = imagecreatetruecolor($pixels, $pixels);
        //copy the original image onto the canvas
        imagecopyresampled( $tmp_canvas, $src, 0, 0, $crop_x, $crop_y, $pixels, $pixels, $shortest_side, $shortest_side  );

        $file_path = $target_directory . $uniquehash . '_' . $size_name . '.jpg';

        $did_create = imagejpeg($tmp_canvas, $file_path, 70);
        imagedestroy( $tmp_canvas );
      }//end foreach size
      //clean up the original full image
      imagedestroy( $src );

      //if the image saved successfully, store the unique hash in the db.
      if( $did_create ){
        $user_id = $logged_in_user['user_id'];
        $query = "INSERT INTO posts
                  (image, user_id, date, is_published)
                  VALUES
                  ('$uniquehash', $user_id, now(), 0)";
                  $result = $db->query( $query );

                  if( ! $result OR $db->affected_rows != 1 ){
                    //it didn't work
                    $did_create = false;
                    $errors['db'] = 'Error saving to the Database.';
                  }
                  //grab the ID of the new post for step 2
                  $post_id = $db->insert_id;
      }//end if did_create

  }else{
    $errors['size'] = 'That is not an image. Stop trying to hack my site.';
    $did_create = false;
  }
  //if everything worked, redirect to step 2
  if( $did_create ){
    header("Location:edit-post.php?post_id=$post_id");
  }else{
    $feedback = 'There was a problem uploading the file';
  }
}//end parser
