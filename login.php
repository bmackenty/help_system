<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

    <?php 
        session_start();
        include('help_navbar.php'); 
    ?>
    <div class="container mt-5">

<?php 
if ($_SESSION['error_wrong_password']){
    ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Error:</strong> Wrong Password. Please try again.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
}
unset($_SESSION['error_wrong_password']);
?>


<?php 
if ($_SESSION['error_no_username']){
    ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Error:</strong> There is no username like that in our system.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php
}
unset($_SESSION['error_no_username']);
?>

<div class="alert alert-primary alert-dismissible fade show" role="alert">
        Please login.
    </div>



    <form action="login_process.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input name="username" type="text" class="form-control" id="username" aria-describedby="username" placeholder="Please type your username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Please type your password">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

<?php
// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
?>
</div> <!-- close the container -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
