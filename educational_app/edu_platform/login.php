<?php
// Initialize the session
session_start();

// If already logged in and is admin, redirect to admin dashboard
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["user_type"] === 'admin') {
    header("location: adminDashboard.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no input errors, try login
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username_out, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct — start new session
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username_out;
                            $_SESSION["user_type"] = "admin";

                            header("location: adminDashboard.php");
                            exit;
                        } else {
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    $login_err = "No account found with that username.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background:rgb(255, 255, 255);
        }
        
        .wrapper{
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            text-align: center;
        }
        
        input[type="text"],
        input[type="password"] {
            background-color:rgb(220, 232, 245);
            color: black;
            padding: 10px 20px;
            border: none;
            margin-top: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        input:hover {
            background-color: #0056b3;
            color: thistle;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2 class="text-center">Admin Login</h2>
    <p class="text-center">Enter your credentials to access the admin panel.</p>

    <?php 
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
        </div>
            <p>Are you a student? <a href="student_login.php">Log in as Student</a></p>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            <b><p class="mt-2">Back to <a href="home.php">Home</a>.</p></b>
        </form>
    </form>
</div>
</body>
</html>
