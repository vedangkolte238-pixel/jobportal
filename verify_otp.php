<?php

session_start();

include "db.php";

if (!isset($_SESSION["verify_email"])) {
    exit("Verification session expired. Please register again.");
}

$email = $_SESSION["verify_email"];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = trim($_POST["otp"]);

    if (!preg_match("/^[0-9]{6}$/", $otp)) {

        $message = "Please enter a valid 6-digit OTP.";

    } else {

        $query = mysqli_prepare(
            $conn,
            "SELECT id, otp, otp_expiry, email_verified FROM users WHERE email=?"
        );

        mysqli_stmt_bind_param(
            $query,
            "s",
            $email
        );

        mysqli_stmt_execute($query);

        $result = mysqli_stmt_get_result($query);

        $user = mysqli_fetch_assoc($result);

        mysqli_stmt_close($query);


        if (!$user) {

            $message = "User not found.";

        } elseif ($user["email_verified"] == 1) {

            $message = "Email is already verified.";

            unset($_SESSION["verify_email"]);

        } elseif ($user["otp"] === null) {

            $message = "OTP not found. Please register again.";

        } elseif ($user["otp"] != $otp) {

            $message = "Invalid OTP.";

        } elseif (empty($user["otp_expiry"])) {

            $message = "OTP expiry time not found.";

        } elseif (strtotime($user["otp_expiry"]) < time()) {

            $message = "OTP has expired. Please register again.";

        } else {

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET email_verified=1, otp=NULL,otp_expiry=NULL WHERE email=?"
            );

            if (!$update) {

                $message = "Database prepare error: " . mysqli_error($conn);

            } else {

                mysqli_stmt_bind_param(
                    $update,
                    "s",
                    $email
                );

                if (mysqli_stmt_execute($update)) {

                    $affected = mysqli_stmt_affected_rows($update);

                    if ($affected > 0) {

                        mysqli_stmt_close($update);

                        unset($_SESSION["verify_email"]);

                        header("Location: login.html?verified=success");
                        exit();

                    } else {

                        $message = "Verification ran, but the user was not updated.";

                        mysqli_stmt_close($update);
                    }

                } else {

                    $message = "Database update error: " .mysqli_stmt_error($update);

                    mysqli_stmt_close($update);
                }
            }
        }
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

<head>

<title>Email Verification</title>

<style>

body {
    margin: 0;
    min-height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;

    font-family: Arial, sans-serif;
    color: white;

    background:
        radial-gradient(
            circle at 15% 20%,
            rgba(34, 211, 238, 0.12),
            transparent 30%
        ),
        radial-gradient(
            circle at 85% 25%,
            rgba(59, 130, 246, 0.10),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #01080f,
            #01151a,
            #02262d,
            #032f42,
            #071d3a,
            #020914
        );
}

.otp-box {

    width: 350px;

    padding: 35px;

    text-align: center;

    background: rgba(5, 25, 40, 0.75);

    backdrop-filter: blur(20px);

    border-radius: 18px;

    border: 1px solid rgba(165, 243, 252, 0.15);

    box-shadow:
        0 20px 50px rgba(0, 0, 0, 0.40),
        0 0 30px rgba(34, 211, 238, 0.12);
}

h2 {

    color: #a5f3fc;

    margin-bottom: 15px;

    text-shadow:
        0 0 15px rgba(103, 232, 249, 0.35);
}

p {

    color: #dbeafe;

    line-height: 1.5;
}

input {

    width: 90%;

    padding: 13px;

    margin-top: 15px;

    border-radius: 8px;

    border: 1px solid rgba(165, 243, 252, 0.15);

    outline: none;

    background: rgba(2, 15, 27, 0.75);

    color: white;

    text-align: center;

    font-size: 20px;

    letter-spacing: 5px;
}

input:focus {

    border-color: #67e8f9;

    box-shadow:
        0 0 15px rgba(103, 232, 249, 0.20);
}

button {

    width: 98%;

    padding: 13px;

    margin-top: 20px;

    border: none;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            #081644,
            #12396e,
            #138dcc
        );

    color: white;

    font-size: 16px;

    cursor: pointer;
}

button:hover {

    box-shadow:
        0 0 25px rgba(103, 232, 249, 0.40);

    transform: translateY(-2px);
}

.error {

    color: #fca5a5;

    margin-top: 15px;
}

</style>

</head>

<body>

<div class="otp-box">

<h2>Email Verification</h2>

<p>
Enter the 6-digit OTP sent to your email.
</p>

<form method="POST">

<input
    type="text"
    name="otp"
    maxlength="6"
    minlength="6"
    pattern="[0-9]{6}"
    inputmode="numeric"
    placeholder="000000"
    required
>

<button type="submit">
Verify Email
</button>

</form>

<?php

if ($message != "") {

    echo "<p class='error'>";
    echo htmlspecialchars($message);
    echo "</p>";

}

?>

</div>

</body>

</html>