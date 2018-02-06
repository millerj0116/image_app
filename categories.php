<?php
include('includes/header.php');
$cat_id = $_GET['category_id'];
?>
      <main class="content cf">

        <?php //Get all the published posts, newest first.
          $query =   "SELECT posts.post_id, posts.title, posts.body, posts.image, posts.date, users.username, users.user_id, users.avatar, categories.name
                      FROM posts, users, categories
                      WHERE posts.is_published = 1
                      AND posts.user_id = users.user_id
                      AND posts.category_id = categories.category_id
                      AND posts.category_id = $cat_id
                      ORDER BY posts.date DESC
                      LIMIT 20";
          //Run it
          $result = $db->query($query);
          //Check it - are there rows of data to show?
          if( $result->num_rows >= 1 ){
            //loop it
            while( $row = $result->fetch_assoc() ){
          ?>

        <article class="post cf">
          <h2>
            <?php show_avatar( $row['user_id'], 50 ); ?>
            Posted by: <?php echo $row['username'] ?>
          </h2>
          <a href="single.php?post_id=<?php echo $row['post_id'] ?>">
          <img src="<?php echo $row['image'] ?>" alt="TITLE" class="post_img">
          </a>

          <div class="post-info">
            <h3><?php echo $row['title']; ?></h3>
            <h4><?php echo $row['name'] ?></h4>
            <p><?php echo $row['body']; ?></p>
            <span class="date">Posted On: <?php echo convert_date($row['date']); ?></span>
            <span class="comment-count"><?php count_comments( $row['post_id'] ); ?></span>
          </div>
        </article>
      <?php
    }//end while
    //Free the beast
      $result->free();
        }else{
        //No rows found
        echo 'Sorry there are no posts to show here!';
      } ?>

      </main>

      <?php include('includes/sidebar.php'); ?>
      <?php include('includes/footer.php'); ?>
