
    <?php 
    session_start();
    $id = $_GET['id'];
    include('database_inc.php');
    $logged_in_role = $_SESSION['logged_in_role'];
    $logged_in = $_SESSION['logged_in'];
    if(empty($logged_in)) {
        header('location:login.php');
    } elseif ($logged_in_role != "admin") {
        header('location:login.php');
    } else {

    $result = mysqli_query($connect,"DELETE FROM queue WHERE id = '$id';");
    header('location:admin.php');

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
    }
