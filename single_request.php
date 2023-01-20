<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
</head>
<body>
<?php

if(!isset($_GET['id'])) {
    header('location:index.php');
} elseif (!preg_match('/^[0-9]{1,5}$/',$_GET['id'])) {
    header('location:index.php');
} else {
    $request_id=$_GET['id'];

 function time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
  );
  foreach ($string as $k => &$v) {
      if ($diff->$k) {
          $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
          unset($string[$k]);
      }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

session_start();
include('database_inc.php');
$id_of_logged_in_user = $_SESSION['logged_in_id'];
if(!$_SESSION['logged_in']) {
    header('location:index.php');
} else {
    $query_my_tickets = mysqli_query($connect, "SELECT * FROM queue WHERE user_id = '$id_of_logged_in_user' AND id = '$request_id';");
    include('database_inc.php');
    include('help_navbar.php');
    if(mysqli_num_rows($query_my_tickets) == 0){
      ?>
          <div class="container mt-4">
          <div class="alert alert-info" role="alert">
            No help requests! <a href="index.php" class="alert-link">Please click here to ask for help</a>. Please remember, asking for help is an important part of being a good student. 
          </div>
      <?php 
      } else {
      ?>
    <div class="container mt-4">
    <div class="alert alert-info" role="alert">
      </p>You are looking at a single help request. You can reply to each other's requests.  </p>
          </div>

 <?php 
    
    while($result_my_tickets = mysqli_fetch_array($query_my_tickets)){
      ?>
<h2>Orignal Post:</h2>
<p class="mb-1"><strong>Posted:</strong> <?php echo $result_my_tickets['date_added'] . " <small>(" . time_elapsed_string($result_my_tickets['date_added']) . ")</small>"; ?></p>
<p class="mb-1"><strong>Description:</strong><?php echo $result_my_tickets['description']; ?></p>
<p class="mt-1"><strong>Priority:</strong><?php echo $result_my_tickets['priority']; ?></p>
<hr />
<?php
        $current_request=$result_my_tickets['id'];
        $query_child_posts = mysqli_query($connect, "SELECT * from queue_forum WHERE parent_id = '$current_request';");
        if(mysqli_num_rows($query_child_posts) != 0){
          ?>
          <ol>
          <?php
          while($result_child_posts = mysqli_fetch_array($query_child_posts)){
            ?>
            <p><li> On <i><?php echo $result_child_posts['date_added'] . " <small>(" . time_elapsed_string($result_child_posts['date_added']) . ")</small></i> " . $result_child_posts['user_id'] . " posted: "; ?></li></p>
            <p>&nbsp; &nbsp; <?php echo $result_child_posts['post']; ?> (<a href=" " data-toggle="modal" data-target="#reply_to_post">Reply</a>)</p>
            <?php 
          }
          echo "</ol>";
        } // end conditional for child posts
    ?>


<!-- Modal -->
<div class="modal fade" id="reply_to_post" tabindex="-1" role="dialog" aria-labelledby="post_reply" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Post reply</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="queue_post_reply.php" method="POST">
        <div class="form-group">
            <label for="reply">Type your reply here:</label>
            <textarea class="form-control" name="reply" id="reply" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" name="parent_post" value="<?php echo $request_id; ?>">
        <button type="submit" class="btn btn-primary">Submit reply</button>
        </form>
      </div>
    </div>
  </div>
</div>

    </tr>
    <?php 
    }     // end while loop with results

    ?>
    </tbody>
    </table>
    <?php 
} 
}// end logged in conditional
}// end of check to see if there is ID parameter in URL
// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";


?>

</div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



  </body>
</html>