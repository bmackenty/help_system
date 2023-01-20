<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
<?php
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
    $query_my_tickets = mysqli_query($connect, "SELECT * FROM queue WHERE user_id = '$id_of_logged_in_user';");
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
      </p>These are all your help requests, open and closed. </p>
          </div>
    <table id="myTable" class="table table-hover">
  <thead>
    <tr>
      <th>status</th>
      <th>Created</th>
      <th>Snippet</th>
      <th>Priority</th>
      <th>Notes</th>
    </tr>
  </thead>
  <tbody>
 <?php 
    
    while($result_my_tickets = mysqli_fetch_array($query_my_tickets)){

    ?>
    <tr>
      <td><?php echo $result_my_tickets['status']; ?></td>
      <td><?php echo time_elapsed_string($result_my_tickets['date_added']); ?></td>
      <td><a href="view_request.php?id=<?php echo $result_my_tickets['id']; ?>"><?php echo substr($result_my_tickets['description'],0,30) . "..."; ?></a></td>
      <td><?php echo $result_my_tickets['priority']; ?></td>
      <td><?php echo $result_my_tickets['id']; ?></td>

    </tr>
    <?php 
    }     // end while loop with results
    ?>
    </tbody>
    </table>
    <?php 
} 
}// end logged in conditional

?>

</div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable();
} );
</script>
  </body>
</html>