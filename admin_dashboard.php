<?php

session_start();

include "db.php";


if(!isset($_SESSION['admin'])){

header("location:admin_login.html");
exit();

}


$result=mysqli_query($conn,

"SELECT 
users.fullname,
users.email,
jobs.job_title,
jobs.company,
applications.applied_on

FROM applications

JOIN users 
ON applications.user_id=users.id

JOIN jobs
ON applications.job_id=jobs.id"

);


?>


<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="admin.css">

</head>


<body>


<h1>Job Applications</h1>


<table>


<tr>

<th>Name</th>
<th>Email</th>
<th>Job</th>
<th>Company</th>
<th>Date</th>

</tr>



<?php while($row=mysqli_fetch_assoc($result)){ ?>


<tr>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['job_title']; ?></td>

<td><?php echo $row['company']; ?></td>

<td><?php echo $row['applied_on']; ?></td>


</tr>


<?php } ?>


</table>


</body>

</html>