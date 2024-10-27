<?php
include './db_connect.php';
    $passMiss = false;
    $userNotFound = false;
    $login = false;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $sql = "SELECT `name` , `pass` FROM users where email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            if(password_verify($pass, $row['pass'])){
                echo "passwords match. LOGGED IN!";
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $name = explode(" ", $row['name']);
                $_SESSION['name'] = $name[0];
                header("location:./index.php");
                exit();
            }
            else{
                $passMiss = true;
            }
        }
        else{
            $userNotFound = true;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./css/login.css" />
</head>
<body>
    <?php
        if($userNotFound){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> You do not have an account with us! .
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </button>
                </div>';
        }

        if($passMiss){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Password Mismatch.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } 
    ?>

    <div class="login-container">
        <!-- ACM Logo on Top -->
        <div class="logo-container">
            <img src="./images/acm_full-removebg-preview.png" alt="ACM Logo" style="scale:2.5;" />
        </div>
        <!-- Login Form -->
        <h2>Login to Your Account</h2>
        <form method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="pass" placeholder="Enter your password">
            </div>
            <button type="submit">Login</button>
            <div class="form-links">
                <a href="forgot.php">Forgot Password?</a> | <a href="index.php?signup=true">Create an Account</a>
            </div>
        </form>
    </div>
</body>
</html>