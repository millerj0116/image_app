<?php include('includes/header.php');
//pagination config
$per_page = 1;

  $phrase = clean_string($_GET['phrase']);
?>
<main class="content">
  <?php //make sure the phrase isn't blank
  if( $phrase != ''){
    //get all the posts that contain the phrase in their title or body. Posts must be published.
    $query = "SELECT posts.title, posts.date, posts.post_id, posts.body, posts.image, users.user_id, users.username
    FROM posts, users
    WHERE posts.user_id = users.user_id
    AND posts.is_published = 1
    AND ( posts.title LIKE '%$phrase%'
        OR posts.body LIKE '%$phrase%' )
    ORDER BY date DESC";

    $result = $db->query( $query );
    $total = $result->num_rows;

    //How many pages do we need? Round UP using ceil.
    $total_pages = ceil($total / $per_page);

    //figure out which page we are on. If no page, set to 1.
    //Query string will look like: search.php?phrase=blah&page=2
    if( $_GET['page'] ){
      $current_page = $_GET['page'];
    }else{
      $current_page = 1;
    }

    //if they go out of bounds, send to last page
    if( $current_page > $total_pages ){
      $current_page = $total_pages;
    }

    //Update the original query with a LIMIT
    $offset = ($current_page - 1) * $per_page;

    $query .= " LIMIT $offset, $per_page";
    //Run it again with the limit
    $result = $db->query( $query );

    ?>
  <header class="search-header">
    <h1>Search Results for <i><?php echo $phrase; ?></i></h1>
    <h2><?php echo $total; ?> posts found</h2>
    <h3>Showing page <?php echo $current_page; ?> of <?php echo $total_pages; ?></h3>
  </header>


<?php //check to make sure there are posts to show
  if( $total >=1 ){
    while( $row = $result->fetch_assoc() ){?>
  <article>
    <a href="single.php?post_id=<?php echo $row['post_id']; ?>">
    <img src="<?php echo $row['image']; ?>" alt="">
    </a>
    <h2 class="user">
      <?php show_avatar( $row['user_id'], 50); ?>
      <?php echo $row['username']; ?>
    </h2>
    <h3><?php echo $row['title']; ?></h3>
    <span class="date"><?php convert_date( $row['date']); ?></span>
    <span class="comments-number"><?php count_comments($row['post_id']); ?></span>
  </article>
<?php
  }//end while
}//End if there are posts ?>

<?php
//pagination interface
  $prev_page = $current_page - 1;
  $next_page = $current_page + 1;
 ?>

<section class="pagination">
  <?php if( $current_page != 1 ){

    ?>
  <a href="search.php?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $prev_page; ?>">&larr; Previous Page</a>
<?php } ?>

<?php if( $current_page < $total_pages ){?>
  <a href="search.php?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $next_page; ?>">Next Page &rarr;</a>
<?php } ?>

</section>
<?php }else{
  echo 'Empty search phrase';
} ?>
</main>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/footer.php'); ?>
