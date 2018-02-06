<?php
/*
This file handles the like/unlike logic and updates the like UI
*/

require('../config.php');
require('../includes/functions.php');

//Incoming Data
$post_id = $_REQUEST['post_id'];
$user_id = $_REQUEST['user_id'];

//Check to see if this user already likes this post.
$query = "SELECT * FROM likes
          WHERE post_id = $post_id
          AND user_id = $user_id
          LIMIT 1";
$result = $db->query( $query );
//If so, RREMOVE the like.
if( $result->num_rows == 1 ){
    $query = "DELETE FROM likes
              WHERE post_id = $post_id
              AND user_id = $user_id
              LIMIT 1";
}else{
//if not, ADD the like.
$query = "INSERT INTO likes
          (post_id, user_id, date)
          VALUES
          ($post_id, $user_id, NOW())";
}
//Run the $query
$result = $db->query($query);
//check to see if it worked (one row will be deleted or added).
if( $db->affected_rows == 1 ){
  //update the UI
  like_interface( $post_id, $user_id );
}else{
  //TODO remove after testing
  echo 'Like failed';
}
