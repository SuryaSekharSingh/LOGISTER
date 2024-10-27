<?php
    include('./db_connect.php');
    session_start();
    
$showAlert = false;
$passMiss = false;
$existUser = false;

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];
        $exists = false;
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

        $numRows = $result->num_rows;
        if($numRows > 0){
            $exists = true;
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACM - Login & Signup</title>
    <link rel="stylesheet" href="./css/index.css">
    <!-- Poppins Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- bootstrap link  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <h1>LOGISTER</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <?php
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    echo '
                    <li><a href="" style="pointer-events:none;cursor:default;text-decoration:none;">Welcome  <span style="color:#ff6600;">' . $_SESSION['name'] . '</span> </a></li>
                    <li><a href="logout.php" class="login-btn">Logout</a></li>
                    ';                    
                }
                else{
                    echo '
                    <li><a href="login.php" class="login-btn">Login</a></li>
                    <li><a href="#signup">Get Started</a></li>
                    ';
                }
            ?>
            
        </ul>
    </nav>
    <?php 
        if($showAlert){
           echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your account is created and you can login now.
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
        if($existUser){
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Username already exists. Please enter a unique username
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Join the Future of Technology</h2>
            <p>Unlock exclusive content, meet tech enthusiasts, and be a part of a growing community.</p>
            <a href="#signup" class="cta-button">Get Started Now</a>
        </div>
        <div class="hero-image">
            <img src="./images/acm_full-removebg-preview.png" alt="Hero Image" style="width:25rem;height:12rem;scale:1.3;    margin-right:1rem;">
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h3>Why Choose Us?</h3>
        <div class="feature-container">
            <div class="feature-item">
                <i class="fas fa-user-shield"></i>
                <h4>Secure Login</h4>
                <p>State-of-the-art login system to protect your information.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-mobile-alt"></i>
                <h4>Responsive Design</h4>
                <p>Optimized for all devices, ensuring seamless user experience.</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-bolt"></i>
                <h4>Fast & Reliable</h4>
                <p>Lightning-fast performance with server-side validations.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <h3>What Our Users Say</h3>
        <div class="testimonials-container">
            <div class="testimonial">
                <p>"This platform made it so easy to connect with fellow tech enthusiasts!"</p>
                <h5>- Alex Smith</h5>
            </div>
            <div class="testimonial">
                <p>"The secure login and easy-to-use interface is top-notch!"</p>
                <h5>- Jane Doe</h5>
            </div>
        </div>
    </section>

    <!-- Signup Section -->
    <section id="signup" class="signup">
        <h3>Get Started</h3>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Enter your Name" required>
            <input type="email" name="email" placeholder="Enter your email" required> 
            <input type="text" name="contact" id="contact" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0,10)" class="contact" placeholder="Enter your Phone Number" required/>
            <input type="password" name="pass" placeholder="Enter your Password" required>
            <input type="password" name="cpass" placeholder="Comfirm Password" required>
            <button type="submit" class="signup-btn" name="register">Sign Up Now</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 LOGISTER. All Rights Reserved.</p>
    </footer>
    
    <?php
        if(isset($_GET['signup']) && $_GET['signup'] == true){
            echo '<script>
                    document.querySelector("#signup").scrollIntoView({
                        behavior: "smooth"
                    }); 
                </script>';
        }
    ?>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
                });
            });
        });    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>
