<section class="comment-form" id="leave-comment">
  <h5>Leave a Comment!</h5>
  <?php if(  isset($feedback) ){
    echo $feedback;
  } ?>
  <form class="" action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $_SERVER['QUERY_STRING']?>#leave-comment" method="post">

    <label for="the_body">Comment:</label>
    <textarea name="body" id="the_body" rows="8" cols="80"></textarea>

    <input type="submit" value="Submit Comment">
    <input type="hidden" name="did_comment" value="1">

  </form>
</section>
