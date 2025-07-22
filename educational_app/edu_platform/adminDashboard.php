<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <header style="background: #007bff; color: white; padding: 20px 40px; text-align: center;">
        <h1 class="my-5">
        <b>
            <?php echo "Welcome, " . htmlspecialchars($_SESSION["username"]);
                ?>
        </b> to EduPlatform </h1>
        <h4><b>"Teaching is the greatest act of optimism."</b></h4>
    </header>
    
    <main style="padding: 40px; text-align: center;">
        <h2>Get Started</h2>
        <p style="max-width: 600px; margin: auto;">
            Access assignments, track grades, participate in forums, and stay organized throughout your educational journey.
        </p>
        <section style="margin-top: 50px; display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
            <div style="flex: 1 1 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <a href="progress_tracking.php"><h3>📊 Progress Tracking</h3></a>
                <p>Track student progress in progress tab.</p>
            </div>
            <div style="flex: 1 1 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <a href="add_assignment.php"><h3>📒 Add Assignment </h3></a>
                <p>Submit and manage assignments easily with deadlines and file upload support.</p>
            </div>
            <div style="flex: 1 1 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <a href="formative_feedback.php"><h3>📩 Formative Feedback</h3></a>
                <p>Feedback provided during the learning process to help students improve their understanding and performance, often before a summative assessment.</p>
            </div>
            <div style="flex: 1 1 300px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <a href="submit_feedback.php"><h3>⏳ Student Information</h3></a>
                <p>Feedback provided previous and current learning process to help students improve their performance.</p>
            </div>
        </section>
        <section style="margin-top: 60px; max-width: 1200px; margin-left: auto; margin-right: auto;">
            <h2>Account Management</h2>
            <p>Manage your account settings, including password resets and profile updates.</p>
            <p>For any issues, please contact our support team.</p>
            <p>We are here to help you succeed!</p>
            <p></p>
            <!-- Responsive Button Group -->
            <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-2 mt-4">
                <a href="reset-password.php" class="btn btn-warning mb-2 mb-sm-0">Reset Your Password</a>
                <a href="logout.php" class="btn btn-danger mx-sm-2 mb-2 mb-sm-0">Sign Out</a>
                <a href="home.php" class="btn btn-primary">Go to Home</a>
            </div>

        </section>
    
    </main>
    <footer style="background: #343a40; color: #ccc; text-align: center; padding: 20px; margin-top: 40px;">
        <p>Contact us:  support@eduplatform.com | +1 (800) 123-4567</p>
        <p>&copy; <?php echo date('Y'); ?> EduPlatform. All rights reserved.</p>
     </footer>
</body>
</html>