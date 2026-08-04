<?php

session_start();

include "db.php";


if(!isset($_SESSION['user_id'])){

    header("location:login.html");
    exit();

}


$user_id = $_SESSION['user_id'];


$user_query = mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'");

$user = mysqli_fetch_assoc($user_query);

$app_query = mysqli_query($conn,

"SELECT jobs.job_title, jobs.company, jobs.location
FROM applications
JOIN jobs
ON applications.job_id = jobs.id
WHERE applications.user_id='$user_id'"

);


?>


<!DOCTYPE html>
<html>

<head>

<title>User Profile</title>

<link rel="stylesheet" href="profile.css">

</head>


<body>


<div class="profile-box">


<h1>My Profile</h1>


<h3>
Name:
<?php echo $user['fullname']; ?>
</h3>


<p>
Email:
<?php echo $user['email']; ?>
</p>


<p>
Phone:
<?php echo $user['phone']; ?>
</p>


<p>
Qualification:
<?php echo $user['qualification']; ?>
</p>


<p>
Skills:
<?php echo $user['skills']; ?>
</p>



<h2>Applied Jobs</h2>


<?php while($job=mysqli_fetch_assoc($app_query)){ ?>


<div class="job">

<h3>
<?php echo $job['job_title']; ?>
</h3>

<p>
Company:
<?php echo $job['company']; ?>
</p>

<p>
Location:
<?php echo $job['location']; ?>
</p>


</div>


<?php } ?>


</div>


</body>

</html>