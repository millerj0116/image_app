<?php
session_start();
//DB Connection
  require('config.php');
  include('includes/functions.php');
  //get all the info about the possibly logged in user.
  $logged_in_user = check_login();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Image Sharing App</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>

    <header class="header">
      <h1><a href="index.php">Image Sharing App</a></h1>
    </header>
    <nav class="main_nav">
      <section class="search-bar">
        <form action="search.php" method="get">
          <label for="the_phrase" class="screen-reader-text">Search:</label>
          <input id="the_phrase" type="search" name="phrase">

          <input type="submit">
        </form>
      </section>


      <ul>
        <?php if( ! $logged_in_user ){ ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>

        <?php }else{ ?>

        <li><a href="profile.php?user_id=<?php echo $logged_in_user['user_id'];?>">Your Profile</a></li>
        <li><a href="#">Edit Profile</a></li>
        <li><a href="add-post.php">Add Post</a></li>
      </ul>
    <?php } ?>


    </nav>

    <div class="wrapper cf">
