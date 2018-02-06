<?php
//Change a timestamp into a human-friendly date

function convert_date( $timestamp ){
  $date = new DateTime( $timestamp );
  return $date->format( 'l, F j, Y' );
}

//Count the number of comments on any post
  function count_comments( $post_id ){
    global $db;
    $query = "SELECT COUNT(*) AS total
              FROM comments
              WHERE post_id = $post_id
              AND is_approved = 1";
    //Run it
    $result = $db->query( $query );
    //Check it
    if( $result->num_rows >=1 ){
      //Display the count
      $row = $result->fetch_assoc();
      //Show the count, plus comment/comments
      //Ternary operator
      echo $row['total'] == 1 ? '1 comment' : $row['total'] . ' comments';

      //Free it
      $result->free();
    }
  }

  //Show any user's avatar at any size. If it doesn't exist, show default image.
  function show_avatar( $user_id, $size = 80 ){
    global $db;

    //Get the avatar of the user specified
    $query = "SELECT avatar, user_id
              FROM users
              WHERE user_id = $user_id
              LIMIT 1
              ";
  $result = $db->query( $query );

  if( $result->num_rows >=1 ){
    while( $row = $result->fetch_assoc() ){
      $userid = $row['user_id'];
      //show their avatar OR a default pic
      if( $row['avatar'] == '' ){
        //default
        $url = 'images/default_user.gif';
      }else{
        $url = $row['avatar'];
      }//end if avatar is blank
    }//end while
  }//end if

  //Show the image
  ?>
  <a href="profile.php?user_id=<?php echo $userid;?>"><img src="<?php echo $url; ?>" alt="Userpic" width="<?php echo $size; ?>" height="<?php echo $size; ?>"></a>
  <?php
}//close function

//Clean String input for the DB
function clean_string( $input ){
  global $db;
  $output = filter_var($input, FILTER_SANITIZE_STRING);
  $output = mysqli_real_escape_string( $db, $output);

  return $output;
}

//Clean Integer input for the DB
function clean_int( $input ){
  global $db;
  $output = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
  $output = mysqli_real_escape_string( $db, $output);

  return $output;
}

//Clean Boolean input for the DB
function clean_boolean( $input ){
  if( $input == 1 ){
    $output = 1;
  }else{
      $output = 0;
  }
  return $output;
}



/*
Check to see if the viewer is a logged in user
*/
function check_login(){
  global $db;

  //Check to see if the user_id and secret_key session vars exist
  if( isset($_SESSION['user_id']) AND isset($_SESSION['secret_key'])  ){
    //check for match in DB
    $user_id = $_SESSION['user_id'];
    $secret_key = $_SESSION['secret_key'];
    $query = "SELECT *
              FROM users
              WHERE user_id = $user_id
              AND secret_key = '$secret_key'
              LIMIT 1";
    $result = $db->query( $query );

    if( !$result ){
      //query failed. not logged in.
      return false;
    }
    if( $result->num_rows == 1 ){
      //SUCCESS. Logged in. Return the info about user.
      return $result->fetch_assoc();

    }else{
      //no rows found
      return false;
    }

  }else{
    return false;
  }
}

/*
  Count likes on any post
*/
function count_likes( $post_id ){
  global $db;
  $query = "SELECT COUNT(*) AS total_likes
            FROM likes
            WHERE post_id = $post_id";
  $result = $db->query( $query );
  if( ! $result ){
    echo $db-error;
  }

  $row = $result->fetch_assoc();
  $total = $row['total_likes'];
  return $total == 1 ? "1 Like" : "$total Likes";
}

/*
User interface for the like button
@param post_id int The post that we are liking/unliking
@param user_id int The logged in user
*/
function like_interface( $post_id, $user_id = 0 ){
  global $db;
  //did this user like this post
  if( $user_id ){
    $query = "SELECT COUNT(*) AS you_like
              FROM likes
              WHERE user_id = $user_id
              AND post_id = $post_id";
    $result = $db->query( $query );
    if( ! $result ){
      echo $db->error;
    }
    $row = $result->fetch_assoc();
    $class = $row['you_like'] ? 'liked' : 'unliked';
  }//end if user_id exists
  ?>
  <span class="like-interface">
    <div class="<?php echo $class; ?>">
      <?php if( $user_id ){ ?>
      <span class="heart-button" data-postid="<?php echo $post_id; ?>">&hearts;</span>
    <?php }//end if logged in ?>
      <?php echo count_likes($post_id); ?>
    </div>
  </span>
  <?php
}

//ARRAY TO LIST
function array_to_list($array = array()){
  if(!empty($array)){
    echo '<ul>';
    foreach ( $array as $key => $value ) {
      echo '<li>' . $value . '</li>';
    }
    echo '</ul>';
  }
}

//DISPLAY AN IMAGE FROM THE DB at any known size
function post_image_url( $image, $size = 'medium'){
  return 'uploads/' . $image . '_' . $size . '.jpg';
}

//OUTPUT ALL CATEGORIES AS A FORM <select> ELEMENT
function category_dropdown( $current = 0 ){
  global $db;

  $query = "SELECT * FROM categories";
  $result = $db->query( $query );

  if( ! $result ){
    return false;
  }
  if( $result->num_rows >= 1){
    ?>
    <select name="category_id">
      <?php while( $row = $result->fetch_assoc() ){ ?>
      <option value="<?php echo $row['category_id']; ?>" <?php
      if( $row['category_id'] == $current ){
        echo 'selected';
      } ?>><?php echo $row['name']; ?></option>
    <?php }//end while ?>
    </select>
    <?php
  }
}

//CHECKBOX HELPER
function checked( $thing1, $thing2 ){
  if( $thing1 == $thing2 ){
    echo 'checked';
  }
}

//no close to php
