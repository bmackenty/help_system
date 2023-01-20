<?php
// this file should be named database_inc.php
$connect = mysqli_connect("localhost","mackenty_queue","PASSWORD HERE","learning");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>