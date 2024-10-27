<?php
include './db_connect.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    //sql queries...
    $sql = "select email from users where email=?";
    
    //preparing statement
    $stmt = $conn->prepare($sql);

    //bind parameter (s=string, i=integer d=double b=blob)
    $stmt->bind_param("s", $email);

    //execute the query
    $stmt->execute();

    //get the result
    $result = $stmt->get_result();

    //fetch data
    // while($row = $result->fetch_assoc()){
    //     echo 'email = ' . $row['email'] . ' name = ' . $row['name']. "<br>";
    // }
    $numRows = $result->num_rows;
    if($numRows > 0){
        $exists = true;
        echo "USER EXISTS ";
    }

    if($pass == $cpass && $exists == false){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, phone, pass) VALUES (?, ? , ? , ?)";
        //prepare statement
        $stmt = $conn->prepare($sql);

        //bind paramters
        $stmt->bind_param("ssss", $name, $email, $contact, $hash);


        //execute statement
        $stmt->execute();
        $result = $stmt->get_result();

        if($result){
            $showAlert = true;
            echo "DATA INSERTED";
        }
    }
    else if($pass != $cpass){
        $passMiss = true;
    }
    else{
        $existUser = true;
    }
    $stmt->close();
}
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body>
    <div class="container">
        <div class="login-box">
            <form action="" method="post">
                <h2>REGISTER</h2>
                <div class="input-box">

                    <label for="email">EMAIL : </label>
                    <input type="email" name="email" id="email" class="email" required/>
                    
                    <label for="name">NAME : </label>
                    <input type="text" name="name" id="name" class="name" required/>
                    
                    <label for="contact">contact : </label>
                    <input type="text" name="contact" id="contact" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0,10)" class="contact" required/>

                    <label for="pass">PASSWORD : </label>
                    <input type="password" name="pass" id="pass" class="pass" required/>

                    <label for="cpass">confirm PASSWORD : </label>
                    <input type="password" name="cpass" id="cpass" class="cpass" required/>

                    <button>SignUp</button>
                </div>

            </form>
        </div>
    </div> -->

    <?php

        if($showAlert)
            echo "Registration Successful!";
        if($passMiss)
            echo "Passwords do not match!";
        if($existUser)
            echo "User already exist! Kindly Login...";
    ?>
</body>
</html>