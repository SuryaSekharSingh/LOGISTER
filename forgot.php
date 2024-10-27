<?php
    include('./db_connect.php');
    session_start();
    $find = false;
    $userNotFound = false;
    $passMiss = false;
    $showAlert = false;
    if(isset($_POST['find'])){
        $email = $_POST['email'];
        $emailRef = $email;
        $phone = $_POST['phone'];
        $sql = "SELECT * FROM users WHERE email=? AND phone=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $find = true;
            $_SESSION['email'] = $email;
        }
        else{
            $userNotFound = true;
        }
    }

    if(isset($_POST['reset'])){
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];
        if($pass == $cpass){
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET pass=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hash, $_SESSION['email']);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){
                $showAlert = true;
                session_unset();
                session_destroy();
                header("location:login.php");
                exit(0);
            }
        }
        else{
            $passMiss = true;
        }
    }
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap link  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <link rel="stylesheet" type="text/css" href="./css/login.css" />
</head>
<body>
        <?php
            if($showAlert){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Your password has been reset and you can login now.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </button>
                    </div>'; 
            }

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
        <h2>Reset your Password</h2>
        <form method="POST">
            <?php 
            if($find == false){
                echo '<div class="input-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="input-group">
                <label for="contact">Your Contact</label>
                <input type="text" id="contact" name="phone" oninput="this.value = this.value.replace(/[^0-9]/g, \'\').substring(0,10)" placeholder="Enter contact no">
            </div>
            <button type="submit" name="find">Find Account</button>';
            }
            else{
                echo '<div class="input-group">
                <label for="password">Enter new Password</label>
                <input type="password" id="password" name="pass" placeholder="Enter new password">
            </div>
            <div class="input-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" id="cpassword" name="cpass" placeholder="Confirm password">
            </div>
            <button type="submit" name="reset">Reset Password</button>
            ';
            }
            ?>    
            <div class="form-links">
                <a href="./login.php">Back to Login?</a> | <a href="index.php?signup=true">Create an Account</a>
            </div>        
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>
</html>