<aside class="sidebar">

<?php if( $logged_in_user ){

   ?>
<section class="logged_in">
  <h2>You are logged in</h2>
  <?php show_avatar( $logged_in_user['user_id'] ) ?>
  <h3><?php echo $logged_in_user['username']; ?></h3>

  <a href="login.php?action=logout">Logout</a>
</section>
<?php } ?>

  <?php //Get up to 5 most recently joined users
  $query = "SELECT username, avatar, user_id
            FROM users
            WHERE did_confirm = 1
            ORDER BY join_date DESC
            LIMIT 5";

  //Run it
  $result = $db->query( $query );
  //Check it
  if( $result->num_rows >=1 ){

  ?>
  <section class="widget recent">
    <h2>Newest Users</h2>

    <?php while( $row = $result->fetch_assoc() ){ ?>
    <a href="PROFILE_URL" title="<?php echo $row['username']; ?>">
      <?php show_avatar( $row['user_id'], 50 ); ?>
    </a>
  <?php }//end while loop
  $result->free();
   ?>
  </section>
<?php }//end if there are users to show ?>


<?php //Get all the categories in random order
  $query = "SELECT categories.*, COUNT(*) AS total
            FROM categories, posts
            WHERE categories.category_id = posts.category_id
            GROUP BY categories.category_id
            ORDER BY RAND()
            LIMIT 10";
  //Run it
  $result = $db->query( $query );
  //Check it
  if( $result -> num_rows >=1 ){

 ?>
  <section class="widget">
    <h2>Categories</h2>
    <ul>
    <?php while( $row = $result->fetch_assoc() ){ ?>
    <li><a href="categories.php?category_id=<?php echo $row['category_id'] ?>"><?php echo $row['name']; ?></a> (<?php echo $row['total'] ?>)</li>
  <?php }//end while
  //Free it
    $result->free();
   ?>
 </ul>
  </section>
  <?php } //End if there are no categories?>

<?php //Get all the tags in alpha order.
  $query = "SELECT name
            FROM tags
            ORDER BY name ASC
            LIMIT 10";
  //Run it
  $result = $db->query( $query );
  //check it
  if( $result -> num_rows >=1 ){
 ?>

  <section class="widget">
    <h2>Popular Tags</h2>
    <ol>
      <?php while( $row = $result->fetch_assoc() ){ ?>
      <li><a href="#"><?php echo $row['name']; ?></a></li>
    <?php }//end while
    //Free it
      $result->free();
       ?>
    </ol>
  </section>
<?php }//End check ?>
</aside>
