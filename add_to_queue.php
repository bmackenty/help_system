<?php 
session_start();
include('database_inc.php');

// $_SESSION['logged_in'] = True;
// $_SESSION['logged_in_user'] = $username;
$logged_in_id = $_SESSION['logged_in_id'];
// $_SESSION['logged_in_role'] = $row['role'];

$query_active_year = mysqli_query($connect,"SELECT description FROM academic_year WHERE status = 'active';");
while($row_active_year = mysqli_fetch_array($query_active_year)){
    $active_year = $row_active_year['description'];
}

$anon = $_POST['anon'];

$first_name = $_POST['first_name'];
$first_name = mysqli_real_escape_string($connect, $first_name);

$course_id = $_POST['course_id'];
$course_id =  mysqli_real_escape_string($connect,$course_id);

$priority = $_POST['priority'];
$priority =  mysqli_real_escape_string($connect,$priority);

$description = $_POST['description'];
$description = mysqli_real_escape_string($connect, $description);


$user_ip = $_SERVER['REMOTE_ADDR'];
$status = "open";



    $result = mysqli_query($connect,
    "INSERT INTO `queue` 
    (`student`, `user_id`, `status`, `date_added`, `course_id`, `description`,`IP_addresses`,`priority`, `academic_year`, `anon`) 
    VALUES ('$first_name', '$logged_in_id', '$status', NOW(), '$course_id', '$description','$user_ip','$priority', '$active_year', '$anon');");

$_SESSION['success_issue_added'] = TRUE;
header("location:view_queue.php?id=".$course_id);



// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";

?>
