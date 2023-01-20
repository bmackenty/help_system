<?php 
    session_start(); 
    include('database_inc.php');
    $logged_in = $_SESSION['logged_in'];
    if(empty($logged_in)) {
        header('location:login.php');
    } else {
        $id = $_GET['id'];
        $id = preg_replace("/[^0-9]/", "", $id);

        $query_get_id = mysqli_query($connect,
        "SELECT * FROM queue_forum WHERE id = '$id';");

        if (mysqli_num_rows($result) == 0){
            $_SESSION['error_invalid_id'] = True;
            header('location:index.php');
        }
        $query_update = mysqli_query($connect, "UPDATE queue_forum SET likes = likes + 1 WHERE id = '$id';");
        $return_id = $_GET['return_id'];
        $return_location = "location:view_request.php?id=".$return_id;
        header($return_location);
    }