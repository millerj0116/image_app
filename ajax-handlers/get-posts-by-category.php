<?php
/*
This file outputs HTML for a list of posts in a category.
It has no doctype and is not intended to be viewed in the browser.
*/
//Connect to the DB
require('../config.php');

//TODO: remove this from the final code! This is for testing a slower network.
sleep(3);
//Get the category id data
$category_id = $_REQUEST['catId'];

//Get the published posts in this category
$query = "SELECT posts.image, posts.title, categories.name
        FROM posts, categories
        WHERE posts.category_id = categories.category_id
        AND categories.category_id = $category_id
        AND posts.is_published = 1
        ORDER BY date DESC
        LIMIT 20";

$result = $db->query( $query );
if( ! result ){
  die( $db->error );
}

if( $result->num_rows >= 1 ){
  while( $row = $result->fetch_assoc() ){
?>

<article>
    <img src="<?php echo $row['image']; ?>">
    <h2><?php echo $row['title']; ?></h2>
    <h3><?php echo $row['name']; ?></h3>
</article>
<?php
  }//End while
}//end if
else{
  echo 'No posts in this category';
}
