<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
</head>
<body>

    <?php 
    session_start(); 
    include('database_inc.php');
    $logged_in_user = $_SESSION['logged_in_user'];
    $logged_in = $_SESSION['logged_in'];
    if(empty($logged_in)) {
        header('location:login.php');
    } elseif ($logged_in_user != "bmackenty") {
        header('location:login.php');
    } else {

      $result = mysqli_query($connect,
      "SELECT * FROM queue  ORDER BY date_added;");


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
    <!-- <div class="container mt-5"> -->



    <div class="card">
        <div class="card-header">
        Text replies, all
        </div>
    </div>
<hr />


<table class="table table-hover table-bordered table-striped" id="myTable">
<thead>
    <tr>
      <th scope="col">Student</th>
      <th scope="col">Date added</th>
      <th scope="col">Snippet</th>

    </tr>
  </thead>
  <tbody>

    <?php
      while ($row = mysqli_fetch_array($result))
      {
      $current_id = $row['id'];
    ?>
    <tr>
      <td> <?php echo $row['student']; ?> </td>
      <td>
        <?php 
            $date_added = $row['date_added'];
            echo "<small>" . $row['date_added'] . " (" . time_elapsed_string($date_added) . ")</small>"; 
        ?>
      </td>
      <td>
        <?php
        $description = $row['description'];
        // $description = strip_tags($description);
        echo $description;
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
<hr>
<?php 
    
    ?>
<!-- </div> close the container -->
<?php include('footer.php'); ?>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
$(document).ready( function () {
    $('#myTable').DataTable({
      "pageLength": 50,
      "order": [[ 2, "desc" ]]
    });
} );
</script>

  </body>
</html>