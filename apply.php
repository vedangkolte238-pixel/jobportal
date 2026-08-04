<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: jobs.php");
    exit();
}

$job_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$check = mysqli_query($conn,
"SELECT * FROM applications
WHERE user_id='$user_id'
AND job_id='$job_id'");

if (mysqli_num_rows($check) > 0) {
    header("Location: jobs.php?status=already");
    exit();
}


$query = "INSERT INTO applications (user_id, job_id)
VALUES ('$user_id','$job_id')";

if (mysqli_query($conn, $query)) {
    header("Location: jobs.php?status=success");
} else {
    header("Location: jobs.php?status=error");
}

exit();

?>