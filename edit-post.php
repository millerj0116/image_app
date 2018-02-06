<?php
//STEP 2 OF ADDING A NEW POST
//This form can also be used to edit any of the user's existing posts

//get the post_id from the URL
require('includes/header.php');
require('includes/edit-post-parse.php');
 ?>

<main class="content">
  <h2>Post Details</h2>

  <img src="<?php echo post_image_url( $row['image'], 'large' ); ?>" alt="TITLE" class="post_img">

  <?php if(isset($feedback)){
    echo '<div class="feedback">';
    echo $feedback;
    echo array_to_list($errors);

    echo '</div>';
  } ?>

  <form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post">

    <label>Title</label>
    <input type="text" name="title" value="<?php echo $row['title']; ?>">

    <label>Description</label>
    <textarea name="body" rows="8" cols="80"><?php echo $row['body']; ?></textarea>

    <label>Category</label>
    <?php category_dropdown( $row['category_id'] ); ?>

    <label>
      <input type="checkbox" name="allow_comments" value="1" <?php checked( $row['allow_comments'], 1 ); ?>>
      Allow Comments on this post?
    </label>

    <input type="hidden" name="did_edit" value="1">
    <input type="submit" value="Save Post">

  </form>

</main>

 <?php require('includes/sidebar.php') ?>
 <?php require('includes/footer.php') ?>
