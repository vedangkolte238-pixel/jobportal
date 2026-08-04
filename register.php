
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit("Invalid Request");
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$dob = $_POST["dob"];
$gender = $_POST["gender"];
$qualification = trim($_POST["qualification"]);
$skills = trim($_POST["skills"]);
$password = $_POST["password"];


$check = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    exit("Email already registered.");
}
mysqli_stmt_close($check);


$resumeName = "";

if (isset($_FILES["resume"]) && $_FILES["resume"]["error"] == 0) {

    $allowed = ["pdf", "doc", "docx"];

    $fileName = $_FILES["resume"]["name"];
    $tmpName = $_FILES["resume"]["tmp_name"];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed)) {
        exit("Invalid resume format.");
    }

    $resumeName = time() . "_" . basename($fileName);

    move_uploaded_file($tmpName, "uploads/" . $resumeName);
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users
(fullname,email,phone,dob,gender,qualification,skills,resume,password)
VALUES (?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssss",
    $name,
    $email,
    $phone,
    $dob,
    $gender,
    $qualification,
    $skills,
    $resumeName,
    $hashedPassword
);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo mysqli_error($conn);
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>