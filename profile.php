<?php
include('includes/header.php');
//which post are we trying to view? (URL is single.php?post_id=X)
$userid = $_GET['user_id'];

//Make sure the Post_id is a number
if( ! is_numeric($userid) ){
  die('Invalid Profile!');
}
?>
      <main class="content cf">

        <?php //Get the published post.
          $query =   "SELECT username, user_id, bio, avatar
                      FROM users
                      WHERE user_id = $userid
                      LIMIT 1";
      //Run it
      $result = $db->query($query);
      //Check it - Are there rows of data to show?
      if($result->num_rows >= 1){
    ?>
    <?php while( $row = $result->fetch_assoc() ){
      $user_name = $row['username'] ?>
    <section class="profile">
      <h2>Viewing Profile: <?php echo $row['username']; ?></h2>
        <?php show_avatar( $userid, 100 ); ?>
      <h3>Name: <?php echo $row['username']; ?></h3>
      <h4>Profile Description:</h4>
      <p><?php echo $row['bio'] ?></p>
    </section>
  <?php } ?>
<?php } ?>

  <section class="profile_posts">
    <h5><?php echo $user_name; ?>'s posts</h5>
    <?php include('includes/profile_posts.php'); ?>
  </section>


      </main>

      <?php include('includes/sidebar.php'); ?>
      <?php include('includes/footer.php'); ?>
