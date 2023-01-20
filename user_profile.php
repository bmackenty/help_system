<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <title>Profile page</title>

</head>
<body>

    <?php 
    session_start(); 
    include('database_inc.php');
    $logged_in_user = $_SESSION['logged_in_user'];
    $logged_in = $_SESSION['logged_in'];
    $logged_in_id = $_SESSION['logged_in_id'];
    if(empty($logged_in)) {
        header('location:login.php');
    }


      $query_user = mysqli_query($connect,
      "SELECT * FROM users WHERE id = '$logged_in_id';");



    include('help_navbar.php'); 
    ?>
    <div class="container mt-5">



    <div class="card mb-3">
        <div class="card-header">
        User Profile Page
        </div>
    </div>

<?php
while($rows_user = mysqli_fetch_array($query_user)){
    ?>

<p>Username: <?php echo $rows_user['username']; ?></p>



<?php 
}

?>




<?php
//   echo "<pre>";
//   print_r(get_defined_vars());
//   echo "</pre>";
  ?>


<?php 
    
    ?>
</div> <!-- close the container -->
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