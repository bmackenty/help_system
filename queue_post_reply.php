<?php 
session_start();
include('database_inc.php');
$parent_post = $_POST['parent_post'];
$reply = $_POST['reply'];
$reply = mysqli_real_escape_string($connect,$reply);

$logged_in_user_id = $_SESSION['logged_in_id'];
$query_insert = mysqli_query($connect, "INSERT INTO queue_forum (`parent_id`, `post`, `date_added`,`user_id`) 
VALUES ('$parent_post', '$reply', NOW(), '$logged_in_user_id');");


// bot stuff should go here
// get the post id
//  get the user id




$header_location = "location:view_request.php?id=" . $parent_post;
header($header_location);


// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";