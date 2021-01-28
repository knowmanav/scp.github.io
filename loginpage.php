<?php
require_once "config.php";

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);

    if(empty(trim($_POST["email"]))){
        $username_err = "email cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set the value of param username
            $param_email = trim($_POST['email']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $email_err = "This email is already taken"; 
                }
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $email_err = "Invalid email format";
                }
                else{
                    $email = trim($_POST['email']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_email, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: loginpage.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>






<!DOCTYPE html>
<html>
<head>
	<title>SignUp and Login</title>
	<link rel="stylesheet" type="text/css" href="loginpage.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="website_logo.ico">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <div class="social-container">
                    <!-- <a href="#" class="social"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="social"><i class="fa fa-google"></i></a>
                    <a href="#" class="social"><i class="fa fa-linkedin"></i></a> -->
                </div>
                <!-- <span>or use your email for registration</span> -->
                <input type="text" class="form-control" name="name" id="inputName4" placeholder="Username">
                <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email">
                <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Password">
                <input type="password" class="form-control" name="confirm_password" id="inputPassword" placeholder="Confirm Password">
                <br>
                <button>SignUp</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="#">
                <h1>Sign In</h1>
                <div class="social-container">
                <!-- <a href="#" class="social"><i class="fa fa-facebook"></i></a>
                <a href="#" class="social"><i class="fa fa-google"></i></a>
                <a href="#" class="social"><i class="fa fa-linkedin"></i></a> -->
            </div>
            <!-- <span>or use your account</span> -->
            
            <input type="text" name="name" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <!-- <a href="#">Forgot Your Password</a> -->
            <br>
            <button>Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Already have an account?</h1>
                    <p>The world inside is tempting, why wait? Login now.</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>New here?</h1>
                    <p>Let's get you started at:</p>
                    <img src="logoforlogin.png" width="190px" height="150px">
                    <br><br>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });
        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>


</body>
</html>