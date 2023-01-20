<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c3a846c0f5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css"> 
    <script src="ckeditor/ckeditor.js"></script>
    <link href="ckeditor/plugins/codesnippet/lib/highlight/styles/default.css" rel="stylesheet">
    <script src="ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
    <title> help: edit request</title>
</head>
<body>

    <?php 
    session_start();
    $id = $_GET['id'];
    include('database_inc.php');
    $logged_in_user = $_SESSION['logged_in_user'];
    $logged_in = $_SESSION['logged_in'];
    if(empty($logged_in)) {
        header('location:login.php');
    } elseif ($logged_in_user != "bmackenty") {
        header('location:login.php');
    } else {

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

    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        Edit a help request here <a href="admin.php">Back to admin page</a>
    </div>


<?php
    $result = mysqli_query($connect, "SELECT * FROM queue WHERE id LIKE  '$id';");
while ($row = mysqli_fetch_array($result))
{
  $block = $row['course_id'];
  $course_id_of_request = $row['course_id'];
?>

<form action="admin_edit_process.php" method="POST">

    <div class="form-group">
        <label for="date_closed">IP: <?php echo $row['IP_addresses']; ?></label>
    </div>
  <div class="form-group">
    <label for="first_name">First name:</label>
    <input value="<?php echo $row['student']; ?>"  name="first_name" type="text" class="form-control" id="first_name">
  </div>

  <div class="form-group">
    <label for="status">Status:</label>
    <input value="<?php echo $row['status']; ?>"  name="status" type="text" class="form-control" id="status">
  </div>


  <div class="form-group">
  <?php 
  $selected_priority = $row['priority'];
  ?>
    <label for="priority">Priority</label>
    <select name="priority" class="form-control" id="priority">
      <option <?php if($selected_priority == "1"){echo "selected"; } ?> value="1">Low</option>
      <option <?php if($selected_priority == "5"){echo "selected"; } ?>  value="5">Medium</option>
      <option <?php if($selected_priority == "10"){echo "selected"; } ?>  value="10">High</option>
    </select>
  </div>


  <div class="form-group">
    <label for="course">Course</label>
    <select name="course_id" class="form-control" id="course">
    <?php $query_courses = mysqli_query($connect, "SELECT * FROM courses;"); 
    while($row_courses = mysqli_fetch_array($query_courses)){
      $course_id = $row_courses['id'];
      $course_name = $row_courses['course_name'];
      ?>
      <option <?php if($course_id_of_request == $course_id){echo "selected"; } ?> value="<?php echo $course_id; ?>">
      <?php echo $course_name; ?><small> (<?php echo $row_courses['course_meeting_notes']; ?>)</small>
    </option>
      <?php
    }
    ?>
    </select>
  </div>


  <div class="form-group">
    <label for="date_added">Date added:</label>
    <input value="<?php echo $row['date_added']; ?>"  name="date_added" type="text" class="form-control" id="date_added">
  </div>


 

  <div class="form-group">
    <label for="problem">Describe your problem</label>
    <textarea name="description" class="form-control" id="problem" rows="20"><?php echo $row['description']; ?></textarea>
  </div>

    <input type="hidden" name="id" value = "<?php echo $id; ?>">
    <input type="hidden" name="block" value = "<?php echo $block; ?>">
  <button class="btn btn-primary" type="submit">Click here to update this help request</button>
</form>
<?php 
}
// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
    }
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script>
        CKEDITOR.replace( 'problem' );
    </script>
    <script>hljs.initHighlightingOnLoad();</script>

  </body>
</html>