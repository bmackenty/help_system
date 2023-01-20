<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Data tables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <!-- Font awesome code -->
    <script src="https://kit.fontawesome.com/c3a846c0f5.js" crossorigin="anonymous"></script>

</head>
<body>
    <?php 
    session_start();
    $id = $_GET['id'];
    
    $id = preg_replace("/[^0-9]/", "", $id);
    $course_id = $id;


    include('database_inc.php');
    $logged_in_user = $_SESSION['logged_in_user'];
    $logged_in = $_SESSION['logged_in'];
    if($logged_in_user == "bmackenty" && $logged_in) {
        $edit_mode = True;
    }





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
   

<?php 
if ($_SESSION['error_invalid_id'] == True) {
  ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error</strong> I couldn't retrieve that help request. Sorry! 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php 
unset($_SESSION['error_invalid_id']);
}
?>
  
  
<?php
$result = mysqli_query($connect,
"SELECT * FROM queue WHERE course_id = '$id' AND status = 'open' ORDER BY date_added;");

$result_course_name = mysqli_query($connect, "SELECT * from courses WHERE id = '$id';");
while ($row_name =mysqli_fetch_array($result_course_name)){
  $course_name = $row_name['course_name'];
  $course_meeting_notes = $row_name['course_meeting_notes'];
}


if (mysqli_num_rows($result) == 0){

  ?>

<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>No results</strong> There are no open help requests for this course.
  <a href="statistics.php" class="alert-link">Click here to see our statistics.</a>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>



  <?php
} else {
?>
  <div class="alert alert-primary" role="alert">
  <h3><?php echo $course_name; ?></h3> 
  <h5> <?php echo $course_meeting_notes; ?></h5>
  <br />

  <p>The table below is the current help-queue. 
    Please feel free to click on column heads to learn more about a help request. Also, please use the search box. 
    If you click on the snippet, you can read the entire help request and reply!</p>
    <p>You can click on a column header (try Date added) to sort the table by that column.</p>
</div>

<hr class="mt-4 mb-4">

<table class="mt-3 table table-hover table-bordered table-striped" id="myTable">
  <thead>
    <tr>
    <?php if($edit_mode){
      echo "<th scope=\"col\">Actions</td>";
    }
    ?>
      <th scope="col">Student</th>
      <th scope="col">Date added</th>
      <th scope="col">Snippet</th>
      <th scope="col">Replies</th>
      <th scope="col">Priority</th>
      <th scope="col">Status / Flags</th>
   
    </tr>
  </thead>
<tbody>

<?php

while ($row = mysqli_fetch_array($result))
{
  $current_id = $row['id'];

?>
    <tr>
    <?php if($edit_mode){
      ?>
      <td><a href="admin_edit.php?id=<?php echo $row['id']; ?>">edit</a> | <a href="admin_close.php?id=<?php echo $row['id']; ?>&course_id=<?php echo $course_id; ?>">close</a> | <a href="admin_delete.php?id=<?php echo $row['id']; ?>">delete</a></td>
  <?php 
    }
?>
      <td> 
        <?php 
          if ($row['anon'] == 'anon') {
            echo "a student"; 
          } else {
            echo $row['student'];
          }
        ?> 
      </td>

      <td>
        <?php 
            $date_added = $row['date_added'];
            echo "<small>" . $row['date_added'] . " (" . time_elapsed_string($date_added) . ")</small>"; 
        ?>
      </td>
      <td><a target="_new" href="view_request.php?id=<?php echo $row['id']; ?>">
        <?php
        $description = $row['description'];
        $description = strip_tags($description);
        echo substr($description,0,15) . "...."; ?>
        </a>
      </td>
      <td>
        <?php 
          $query_count_replies = mysqli_query($connect, "SELECT * FROM queue_forum WHERE parent_id = '$current_id';");
          $reply_count = mysqli_num_rows($query_count_replies);
          echo $reply_count;

        ?>

      </td>
      <td>
      <?php 
      $priority = $row['priority'];
          switch($priority) {
          case 1:
            echo "<span class=\"badge badge-success\">Low</span>";
            break;
          case 5:
            echo "<span class=\"badge badge-warning\">Average</span>";
            break;
          case 10:
            echo "<span class=\"badge badge-danger\">High</span>";
            break;
          }
          ?>
    
        </td>
      <td><?php 
      $status = $row['status'];
      $teacher_notes = $row['teacher_notes'];
     
      if ($status == "open"){
      echo "<span class=\"badge badge-success\">$status</span>"; 
      } elseif ($status == "closed") {
        echo "<span class=\"badge badge-danger\">$status</span>"; 
      }

      if (!empty($teacher_notes)) {
        echo " <span class=\"badge badge-info\">teacher note</span>"; 
      }


      ?>
      
    </td>

    </tr>

  <?php
  }
}
//   echo "<pre>";
//   print_r(get_defined_vars());
//   echo "</pre>";
  ?>
  </tbody>
</table>


</div> <!-- close the container -->

    <!-- =========================================== -->
    <!-- PLEASE DO NOT EDIT ANYTHING BELOW THIS LINE -->
    <!-- =========================================== -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
$(document).ready( function () {
    $('#myTable').DataTable({
      "pageLength": 50,
      "order": [[ 1, "asc" ]]
    });
} );
</script>

  </body>
</html>