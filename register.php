<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


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

mysqli_stmt_bind_param(
    $check,
    "s",
    $email
);

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

    $extension = strtolower(
        pathinfo($fileName, PATHINFO_EXTENSION)
    );


    if (!in_array($extension, $allowed)) {
        exit("Invalid resume format.");
    }


    $resumeName = time() . "_" . basename($fileName);

    move_uploaded_file(
        $tmpName,
        "uploads/" . $resumeName
    );
}


$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* Generate 6 digit OTP */

$otp = sprintf(
    "%06d",
    random_int(0, 999999)
);


/* OTP valid for 5 minutes */

date_default_timezone_set('Asia/Kolkata');

$otp_expiry = date(
    "Y-m-d H:i:s",
    strtotime("+5 minutes")
);


/* Insert user */

$sql = "INSERT INTO users
(fullname,email,phone,dob,gender,qualification,skills,resume,password,email_verified,otp,otp_expiry)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


$email_verified = 0;


mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssis",
    $name,
    $email,
    $phone,
    $dob,
    $gender,
    $qualification,
    $skills,
    $resumeName,
    $hashedPassword,
    $email_verified,
    $otp,
    $otp_expiry
);


if (mysqli_stmt_execute($stmt)) {

    /* Send OTP email */

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = 'vedangkolte238@gmail.com';

        $mail->Password = 'ikhwekhhpusrpfoi';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        $mail->setFrom(
            'vedangkolte238@gmail.com',
            'Job Portal'
        );

        $mail->addAddress($email);


        $mail->isHTML(true);

        $mail->Subject = 'Job Portal Email Verification OTP';


        $mail->Body = "
            <div style='font-family:Arial,sans-serif;'>
                <h2>Job Portal Email Verification</h2>

                <p>Hello <b>$name</b>,</p>

                <p>
                    Thank you for registering on our Job Portal.
                </p>

                <p>
                    Your verification OTP is:
                </p>

                <h1 style='color:#155E75;letter-spacing:5px;'>
                    $otp
                </h1>

                <p>
                    This OTP is valid for <b>5 minutes</b>.
                </p>

                <p>
                    Please do not share this OTP with anyone.
                </p>

                <p>
                    Regards,<br>
                    Job Portal Team
                </p>
            </div>
        ";


        $mail->send();


        session_start();

        $_SESSION["verify_email"] = $email;


        header("Location: verify_otp.php");

        exit();


    } catch (Exception $e) {

        echo "Registration successful, but OTP email could not be sent.";

        echo "<br>Mail Error: " . $mail->ErrorInfo;

    }


} else {

    echo mysqli_error($conn);

}


mysqli_stmt_close($stmt);

mysqli_close($conn);

?>