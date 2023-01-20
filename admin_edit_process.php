<?php 
include('database_inc.php');


$first_name = $_POST['first_name'];
$status =  $_POST['status'];
$priority =  $_POST['priority'];
$date_added =  $_POST['date_added'];
$teacher_notes =  $_POST['teacher_notes'];
$private_notes =  $_POST['private_notes'];
$description =  $_POST['description'];
$id =  $_POST['id'];
$course_id = $_POST['course_id'];

$first_name = mysqli_real_escape_string($connect, $first_name);
$description = mysqli_real_escape_string($connect, $description);
$private_notes = mysqli_real_escape_string($connect, $private_notes);
$teacher_notes = mysqli_real_escape_string($connect, $teacher_notes);

    
$result = mysqli_query($connect, "UPDATE queue
SET 
student = '$first_name', 
priority = '$priority',
status = '$status',
date_added = '$date_added',
teacher_notes = '$teacher_notes',
private_notes = '$private_notes',
description = '$description',
block = '$block',
course_id = '$course_id'
WHERE id LIKE $id;");

$full_header = "location:view_queue.php?id=" . $block;
header($full_header);

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";

?>