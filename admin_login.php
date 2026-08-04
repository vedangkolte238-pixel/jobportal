<?php

session_start();

include "db.php";


$username = $_POST['username'];
$password = $_POST['password'];


$query = mysqli_query($conn,
"SELECT * FROM admin 
WHERE username='$username' 
AND password='$password'");


if(mysqli_num_rows($query)>0)
{

$_SESSION['admin']="admin";

header("location:admin_dashboard.php");

}

else{

echo "Invalid Admin Login";

}

?>
