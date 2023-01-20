<?php 
session_start();
$logged_in_id = $_SESSION['logged_in_id'];
$logged_in_role = $_SESSION['logged_in_role'];

include('database_inc.php');

$id = $_GET['id'];
$id = preg_replace("/[^0-9]/", "", $id);

$result = mysqli_query($connect,
"SELECT * FROM queue WHERE id = '$id';");

if (mysqli_num_rows($result) == 0){
    $_SESSION['error_invalid_id'] = True;
    header('location:index.php');
}

while($row=mysqli_fetch_array($result)){
    $author_id = $row['user_id'];
}

if($author_id == $logged_in_id) {
    $query_close_request = mysqli_query($connect, "UPDATE queue SET status = 'closed', date_closed = NOW() WHERE id = '$id';");
    header('location:index.php');
} else {
    header('location:index.php');

}


