
    <?php 
    session_start();
    $id = $_GET['id'];
    $course_id = $_GET['course_id'];

    include('database_inc.php');
    $logged_in_user = $_SESSION['logged_in_user'];
    $logged_in = $_SESSION['logged_in'];
    $Logged_in_role = $_SESSION['role'];
    if(empty($logged_in)) {
        header('location:login.php');
    } elseif ($logged_in_user != "bmackenty") {
        header('location:login.php');
    } else {

        $result = mysqli_query($connect, "UPDATE queue
        SET  
        status = 'closed',
        date_closed = NOW()
        WHERE id LIKE $id;");

    $return_location = "location:view_queue.php?id=$course_id";
    header($return_location);

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
    }
