<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    exit("Invalid Request");
}

$email = trim($_POST["email"]);
$password = $_POST["password"];


$sql = "SELECT * FROM users WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if(mysqli_num_rows($result) == 1){

    $user = mysqli_fetch_assoc($result);


    if(password_verify($password, $user["password"])){

        /* Check email verification */

        if($user["email_verified"] != 1){

            echo "Please verify your email before logging in.";

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            exit();
        }


        session_start();

        session_unset();
        session_destroy();

        session_start();


        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fullname"] = $user["fullname"];
        $_SESSION["email"] = $user["email"];


        echo "success";


    }
    else{

        echo "Incorrect Password.";

    }


}
else{

    echo "Email not found.";

}


mysqli_stmt_close($stmt);

mysqli_close($conn);

?>