<?php include('includes/header.php');
  //Get all the categories for the buttons
  $query = "SELECT * FROM categories";
  $result = $db->query( $query );

  if( ! $result ){
    die( $db->error );
  }
?>
<main class="content">
  <h2>Pick a Category</h2>

  <?php while( $row = $result->fetch_assoc() ){ ?>
  <button class="category-button" data-catid="<?php echo $row['category_id']; ?>">
    <?php echo $row['name']; ?>
  </button>
<?php } ?>

  <div id="display-area">
    Pick a category to see all the posts in it.
  </div>



</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
  //when the user clicks a category, grab the category id

  $('.category-button').click( function(e){
    var catId = $(this).data('catid');
    console.log(catId);

    $.ajax({
      method : 'GET',
      url : 'ajax-handlers/get-posts-by-category.php',
      dataType : 'html',
      data : { 'catId' : catId },
      success : function( response ){
        //display the response on this page
        $('#display-area').html( response );
      },
      error : function(){
        alert('ajax error');
      }
    });
  });

  //Listen for ajax requests to start/stop. Give visual feedback
  $(document).on({
    ajaxStart : function(){
      $('#display-area').addClass('loading');
      $('#display-area').text('Loading, please wait.');
      $('#display-area').append('<img src="images/loading.gif" width=50</img>');
    },
    ajaxStop : function(){
      $('#display-area').removeClass('loading');
    }
  });



</script>


<?php include('includes/sidebar.php'); ?>
