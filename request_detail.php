<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Font awesome code -->
    <script src="https://kit.fontawesome.com/c3a846c0f5.js" crossorigin="anonymous"></script>

  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

</head>
<body>
    <!-- =========================================== -->
    <!-- PLEASE DO NOT EDIT ANYTHING ABOVE THIS LINE -->
    <!-- =========================================== -->

    <?php 
    session_start();

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

    include('help_navbar.php'); 
    ?>
    <div class="container mt-5">



    <div class="card card-success">
  <div class="card-header">
   Request Detail
  </div>
  <div class="card-body">
    <h5 class="card-title">This is information about a single request</h5><br />
    <p class="small">Please <a href="view_queue.php">click here</a> to go back to the list of open issues.</p>
    <p class="card-text">
    
    </p>
  </div>
</div>
<hr />

<?php
include('database_inc.php');

$id = $_GET['id'];
$id = preg_replace("/[^0-9]/", "", $id);

$result = mysqli_query($connect,
"SELECT * FROM queue WHERE id = '$id';");

if (mysqli_num_rows($result) == 0){
    $_SESSION['error_invalid_id'] = True;
    header('location:index.php');
}

while ($row = mysqli_fetch_array($result))
{
    $date_added = $row['date_added'];
    $date_closed = $row['date_closed'];
?>

<p><strong>Student:</strong> <?php echo $row['student']; ?></p>
<p><strong>Block:</strong> <?php echo $row['block']; ?></p>
<p><strong>Date this request was submitted:</strong> <?php echo $date_added . " (" . time_elapsed_string($date_added) . ")"; ?></p>
<?php 
if (!empty($row['date_closed'])) {
?>
<p><strong>Date this request was closed:</strong> <?php echo $date_closed . " (" . time_elapsed_string($date_closed) . ")"; ?></p>
<?php 
}
?>

<p><strong>Status:</strong> 

    <?php $status = $row['status'];
      if ($status == "open") {
          ?>
          <span class="badge badge-success"><?php echo $row['status']; ?></span>
      <?php } elseif ($status == "closed") {
          ?>
          <span class="badge badge-danger"><?php echo $row['status']; ?></span>
      <?php
      }
      ?> 
</p>

<p><strong>Priority:</strong> 
    <?php switch($row['priority']){
          case 1:
              echo "Low";
              break;
          case 5:
              echo "Medium";
              break;
          case 10:
              echo "High";
              break;
          default:
              echo "Normal";
          }
?>

<p><strong>Description:</strong> <textarea class="form-control" rows="20" readonly><?php echo $row['description']; ?></textarea></p>
<?php 
if (!empty($row['teacher_notes'])) {
?>
    <p><strong>Teacher Notes:</strong> <textarea class="form-control" rows="20" readonly><?php echo $row['teacher_notes']; ?></textarea></p>
<?php
}

}
?>


</div> <!-- close the container -->

    <!-- =========================================== -->
    <!-- PLEASE DO NOT EDIT ANYTHING BELOW THIS LINE -->
    <!-- =========================================== -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>