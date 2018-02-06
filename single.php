<?php
include('includes/header.php');
//which post are we trying to view? (URL is single.php?post_id=X)
$post_id = $_GET['post_id'];

//Make sure the Post_id is a number
if( ! is_numeric($post_id)){
  die('Invalid Post!');
}

//Parse the comment
include('includes/comment-parse.php');
?>
      <main class="content cf">

        <?php //Get the published post.
          $query =   "SELECT posts.post_id, posts.title, posts.body, posts.image, posts.date, users.username, users.user_id, users.avatar, categories.name, posts.allow_comments
                      FROM posts, users, categories
                      WHERE posts.is_published = 1
                      AND posts.user_id = users.user_id
                      AND posts.category_id = categories.category_id
                      AND posts.post_id = $post_id
                      LIMIT 1";
          //Run it
          $result = $db->query($query);
          //Check it - are there rows of data to show?
          if( $result->num_rows >= 1 ){
            //loop it
            while( $row = $result->fetch_assoc() ){
              $allow_comments = $row['allow_comments'];
          ?>

        <article class="post cf">
          <a href="single.php?post_id=<?php echo $row['post_id'] ?>">
          <h2>
            <?php show_avatar( $row['user_id'], 50 ); ?>
            Posted by: <?php echo $row['username'] ?>
          </h2>
          <img src="<?php echo post_image_url( $row['image'], 'large' ); ?>" alt="TITLE" class="post_img">
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
    ?>
    <section class="comments">
      <?php //Get all the approved comments about the post, Oldest first
      $query = "SELECT comments.body, comments.date, users.username, users.user_id
                FROM comments, users
                WHERE comments.user_id = users.user_id
                AND comments.is_approved = 1
                AND comments.post_id = $post_id
                ORDER BY date ASC
                LIMIT 30";

    //Run it
    $result = $db->query( $query );
    //Check it
    if( $result->num_rows >= 1 ){ ?>
      <h3>Comments</h3>

      <ol class="comment-list">
        <?php while( $row = $result->fetch_assoc() ){
          ?>
        <li>
            <h4>
              <?php show_avatar( $row['user_id'], 70 ); ?>
              <?php echo $row['username']; ?>
            </h4>
            <p><?php echo $row['body']; ?></p>
            <span class="date"><?php echo convert_date( $row['date'] ); ?></span>
        </li>
      <?php }//end while ?>
      </ol>
    <?php }else{
      echo 'There are no comments yet! Be the first to comment!';
    } ?>
    </section>

    <?php
    if($allow_comments){
    include('includes/comment-form.php');
  }
    ?>

    <?php
    //Free the beast
      $result->free();
        }else{
        //No rows found
        echo 'Sorry there are no posts to show here!';
      } ?>



      </main>

      <?php include('includes/sidebar.php'); ?>
      <?php include('includes/footer.php'); ?>
