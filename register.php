<?php
  //Stand-alone register page.
  //The normal site header is not included on this page.

  require('config.php');
  include_once('includes/functions.php');

  //Parse the register form
  if($_POST['did_register']){
    //sanitize everything for the DB
    $username = clean_string( $_POST['username'] );
    $email = clean_string( $_POST['email'] );
    $password = clean_string( $_POST['password'] );

    if( $_POST['policy'] == 1 ){
      $policy = 1;
    }else{
      $policy = 0;
    }

    //Validate
    $valid = true;
        //Username blank or outside of limits (5-30)
        if( strlen($username) < 5 OR strlen($username) > 30 ){
          $valid = false;
          $errors['username'] = 'Please choose a username between 5 - 30 chatacters';
        }else{
          //Username already taken
          $query = "SELECT username
                    FROM users
                    WHERE username = '$username'
                    LIMIT 1";
          $result = $db->query( $query );
          if( $result->num_rows == 1 ){
            $valid = false;
            $errors['username'] = 'Sorry, that username is already taken!';
          }//end query if
        }//end of username checks


        //Email wrong format
        if( ! filter_var($email, FILTER_VALIDATE_EMAIL) ){
          $valid = false;
          $errors['email'] = 'Please provide a valid email address.';
        }else{
          //Email already taken
          $query = "SELECT email
                    FROM users
                    WHERE email = '$email'
                    LIMIT 1";
          $result = $db->query( $query );
          if( $result->num_rows == 1 ){
            $valid = false;
            $errors['email'] = 'Oops! That email account is already in use. Do you want to login?';
          }
        }//end email checks


        //password too short (less than 8 characters)
        if( strlen($password) < 8 ){
          $valid = false;
          $errors['password'] = 'That password is too short. Please choose a password that is at least 8 characters.';
        }

        //policy not checked
        if( $policy != 1 ){
          $valid = false;
          $errors['policy'] = 'You must agree to our terms to register.';
        }

    //if valid, add a new user to DB
    if( $valid ){
      //Add salt to the password before hashing.
        $salted_password = $password . SALT;
        $secret_key = sha1(microtime() . SALT );
        $query = "INSERT INTO users
                  ( username, email, password, join_date, is_admin, secret_key, did_confirm )
                  VALUES
                  ( '$username', '$email', SHA1('$salted_password'), NOW(), 0, '$secret_key', 0 )";

        $result = $db->query( $query );

        //If that works, send email confirmation, Log them in, etc.
        if( $db->affected_rows == 1 ){
          //DB success!
          $user_id = $db->insert_id;
          //Generate confirmation link
          $confirmation_url = SITE_URL . '/confirm_account.php?user_id=' . $user_id . '&amp;key=' . $secret_key;
          //TODO. Send this in an email. Echo it on the page for now.

          $feedback = "Welcome! You are now a user! A confirmation link was sent to $email";

          $feedback .= '<a href="' . $confirmation_url . '">Confirmation Link</a>';
        }else{
          $feesback = 'User not added to Database';
        }

    }else{
      $feedback = 'Fix the problems, you Bastard!';
    }//end if Valid

    //Show user feedback
  }//end parser
 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="css/style.css">
     <title>Sign Up for an Account</title>
   </head>
   <body>
     <div class="wrapper">
     <h1>Create an Account</h1>

     <?php //Show user feedback
      if( isset($feedback) ){
        echo $feedback;
      }
      ?>

     <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
       novalidate>

       <label for="the_username">Username:</label>
       <input id="the_username" type="text" name="username" required>

       <label for="the_email">Email:</label>
       <input id="the_email" type="email" name="email" required>

       <label for="the_password">Password:</label>
       <input id="the_password" type="password" name="password" required>

       <label>
         <input type="checkbox" name="policy" value="1">
         I agree to the terms of service.
       </label>

       <input type="submit" value="Sign Up">
       <input type="hidden" name="did_register" value="1">

     </form>
     </div>
   </body>
 </html>
