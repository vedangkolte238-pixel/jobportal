<?php
include "db.php";

$result = mysqli_query($conn, "SELECT * FROM jobs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
    <link rel="stylesheet" href="jobs.css">
</head>

<body>

<?php
if (isset($_GET['status'])) {

    if ($_GET['status'] == "success") {
        echo "<script>alert('Application Submitted Successfully!');</script>";
    }

    if ($_GET['status'] == "already") {
        echo "<script>alert('You have already applied for this job.');</script>";
    }

    if ($_GET['status'] == "error") {
        echo "<script>alert('Application Failed. Please try again.');</script>";
    }

}
?>

<header>
    <h1>Job Portal</h1>

    <nav>
        <a href="dashboard.html">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<section class="jobs">

    <h2>Latest Job Openings</h2>

    <div class="job-container">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <div class="job-card">

            <h3><?php echo $row["job_title"]; ?></h3>

            <p><strong>Company:</strong> <?php echo $row["company"]; ?></p>

            <p><strong>Location:</strong> <?php echo $row["location"]; ?></p>

            <p><strong>Salary:</strong> <?php echo $row["salary"]; ?></p>

            <p><strong>Category:</strong> <?php echo $row["category"]; ?></p>

            <p><?php echo $row["description"]; ?></p>

            <a href="apply.php?id=<?php echo $row['id']; ?>">
                <button>Apply Now</button>
            </a>

        </div>

        <?php } ?>

    </div>

</section>

</body>
</html>