<?php
    // Include config file
    require_once "config.php";

    // Define variables and initialize with empty values
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Get user type early
        $user_type = $_POST['user_type'] ?? 'student';
        echo "<pre>DEBUG: User type: " . htmlspecialchars($user_type) . "</pre>";
        $target_table = ($user_type === 'teacher') ? 'admins' : 'users';

        // Validate username
        if (empty(trim($_POST["username"]))) {
            $username_err = "Please enter a username.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
            $username_err = "Username can only contain letters, numbers, and underscores.";
        } else {
            // Prepare a select statement based on user_type
            $sql = "SELECT id FROM $target_table WHERE username = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "This username is already taken.";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter a password.";
        } elseif (strlen(trim($_POST["password"])) < 4) {
            $password_err = "Password must have at least 4 characters.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }

        // Insert into correct table
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO $target_table (username, password) VALUES (?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php");
                    exit;
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
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
            max-width: 470px;
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
            color: white;
        }
        .selection-user {
            text-align: center;
            align-items: center;
        }
        .form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p class="text-center">Create an account to access all features.</p>
        <p>Are you a Student or Teacher. <b>Choose the below option 👇</b> </p>
        <p></p>
        <div class="form">
            <button type="button" class="btn btn-warning" onclick="selectRole('student')">Student</button> 
            <p> or </p>
            <button type="button" class="btn btn-danger" onclick="selectRole('teacher')">Teacher</button>
        </div>
        </br>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="user_type" id="user_type" value="student">
            <div class="form-group">
                <label id="username_label">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset" href="/reset-password.php"> 
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
            <b><p class="mt-2">Back to <a href="home.php">Home</a>.</p></b>
        </form>
    </div>
    <script>
        function selectRole(role) {
            document.getElementById('user_type').value = role;
            console.log("Selected role: " + role);
                if (role === 'teacher') {
                    document.getElementById('username_label').textContent = "Teacher Username";
                } else {
                    document.getElementById('username_label').textContent = "Student Username";
                }
                // Toggle active button style
                document.querySelectorAll(".form-group button").forEach(btn => btn.classList.remove("active-role"));
                const btn = document.querySelector(`button[onclick="selectRole('${role}')"]`);
                if (btn) btn.classList.add("active-role");
            }
    </script>
</body>
</html>