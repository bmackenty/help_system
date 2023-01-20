<?php 
session_start();
$block = $_GET['block'];

if (filter_var($block, FILTER_VALIDATE_INT) === false) {
    header('location:index.php');
} else {
    if ((1 <= $block) && ($block <= 8)) {

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



?>
        
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <!-- =========================================== -->
    <!-- PLEASE DO NOT EDIT ANYTHING ABOVE THIS LINE -->
    <!-- =========================================== -->
    <?php include('help_navbar.php'); ?>
<div class="container mt-5">

<div class="alert alert-primary" role="alert">
 <p>This is the list of students waiting for help for Mr. MacKenty's computer science / programming class. </p>
 <p>This is the help queue for block <?php echo $block;?> <br /><br /><a href="index.php" class="alert-link">Click here to return to our main page</a>.
</div>


<table class="table table-hover table-bordered table-striped">
  <thead>
    <tr>
      <th scope="col">Position</th>
      <th scope="col">Student</th>
      <th scope="col">Description</th>
      <th scope="col">Date added</th>
      <th scope="col">Block</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
<tbody>
  
  
  <?php

include('database_inc.php');
$result = mysqli_query($connect,
"SELECT * FROM queue WHERE block LIKE '$block';");

while ($row = mysqli_fetch_array($result))
{
    $counter = $counter + 1;
?>
    <tr>
      <th scope="row"><?php echo $counter; ?></th>
      <td><?php echo $row['student']; ?></td>
      <td><?php echo $row['description']; ?></td>
      <td>
        <?php 
            $date_added = $row['date_added'];
            echo $row['date_added'] . " (" . time_elapsed_string($date_added) . ")"; 
        ?>
      </td>
      <td><?php echo $row['block']; ?></td>
      <td><?php echo $row['status']; ?></td>
    </tr>

  <?php

}


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
















<?php
// this the second part of the conditional for a valid int from 1 to 8
    } else {
      header('location:index.php');
    } // end check for int between 1 and 8 inclusive
} // end check for valid integer
?>