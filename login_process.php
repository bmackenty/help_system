<?php
session_start();
// This file should be named store_login_process.php
// below we include our database connection

include('database_inc.php');

// the 2 lines below capture the data sent from a form.
$username = $_POST['username'];
$password = $_POST['password'];

// the lines below execute a QUERY to the database. In this case, 
// we SELECT everything from the table 'users'
// we store the query into a variable named "$result"
$result = mysqli_query($connect,
"SELECT * FROM users WHERE username LIKE '$username';");

// the line below tests if our database query found any results. 
if (mysqli_num_rows($result) == 0) {
  $_SESSION['error_no_username'] = True;
  header('location:login.php');

} else {

// the loop below iterates through the results (if there are any)
// we use the actual colum names from our database in the results. 
// the column names need to match exactly to the column names in your database
while ($row = mysqli_fetch_array($result))
{
  $password_in_databases = $row['password'];

  if (password_verify($password,$password_in_databases)) {

    // this conditional is only true is the password which was entered in our form 
    // is the same as the encypted password stored in our database. 

    $_SESSION['logged_in'] = True;
    $_SESSION['logged_in_user'] = $username;
    $_SESSION['logged_in_id'] = $row['id'];
    $_SESSION['logged_in_role'] = $row['role'];

    $role = $row['role'];
    
    if($role == "admin"){
      header('location:admin.php');
    } else {
      header('location:index.php');
    }

  } else {

    // this conditional is true if the password did NOT match. 
    $_SESSION['error_wrong_password'] = True;
    header('location:login.php');
  }
}
} // this closes the condition that checks if there were any results. 

// echo "<pre>";
// print_r(get_defined_vars());
// echo "</pre>";
?>