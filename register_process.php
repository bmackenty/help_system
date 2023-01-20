<?php
session_start();
include('database_inc.php');

$validation =  $_POST['validation'];
if($validation != "warriors@asw") {
    $_SESSION['error_validation'] = TRUE;
    header('location:register_student.php');
    die;
}

$username = $_POST['username'];
$password = $_POST['password'];
$role = "student";

$name = mysqli_real_escape_string($connect,$name);
$safe_password = password_hash($password,PASSWORD_DEFAULT);

$check_for_duplicate_name = mysqli_query($connect, "SELECT * from users WHERE username = '$username';");

if (mysqli_num_rows($check_for_duplicate_name) == 0) {

    $result = mysqli_query($connect,
    "INSERT INTO `users` 
    (`password`, `username`, `role`) 
    VALUES ('$safe_password', '$username', '$role');");
    $_SESSION['success_good_account'] = TRUE;
    header('location:login.php');

} else {

$_SESSION['error_duplicate_name'] = TRUE;
header('location:register_student.php');
}

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
?>