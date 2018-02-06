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
          $query =   "SELECT users.*, posts.*
                      FROM users
	                     LEFT JOIN posts
                       ON ( users.user_id = posts.user_id )

                      WHERE did_confirm = 1
                      AND users.user_id = $userid
                      AND posts.is_published = 1
                      ORDER BY username ASC
                      LIMIT 10";
      //Run it
      $result = $db->query($query);
      //Check it - Are there rows of data to show?
      if($result->num_rows >= 1){

        $count = 1
    ?>
    <?php while( $row = $result->fetch_assoc() ){
      //Only show the user info if first loop through
      if( $count == 1 ){ ?>
    <?php  $user_name = $row['username'] ?>
    <section class="profile">
      <h2>Viewing Profile: <?php echo $row['username']; ?></h2>
        <?php show_avatar( $userid, 100 ); ?>
      <h3>Name: <?php echo $row['username']; ?></h3>
      <h4>Profile Description:</h4>
      <p><?php echo $row['bio'] ?></p>
    </section>
  <?php }//end first loop ?>
    <a href="single.php?post_id=<?php echo $row['post_id']; ?>"><img src="<?php echo post_image_url($row['image'], 'thumbnail'); ?>"></a>

  <?php $count++;
      }//end while loop ?>
<?php } ?>


      </main>

      <?php include('includes/sidebar.php'); ?>
      <?php include('includes/footer.php'); ?>
