<?php
session_start();
require_once "config.php";

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: student_login.php");
    exit;
}

$student_id = $_SESSION["id"];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = trim($_POST["subject"]);
    $submitted_at = date("Y-m-d H:i:s");
    $file_path = "";

    // File upload handling
    if (isset($_FILES["assignment_file"]) && $_FILES["assignment_file"]["error"] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($_FILES["assignment_file"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $targetFilePath)) {
            $file_path = $targetFilePath;
        } else {
            $error = "File upload failed.";
        }
    }

    if (empty($error)) {
        $stmt = $link->prepare("INSERT INTO student_submissions (student_id, subject, file_path, submitted_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $student_id, $subject, $file_path, $submitted_at);

        if ($stmt->execute()) {
            $success = "✅ Assignment submitted successfully!";
        } else {
            $error = "❌ Failed to submit. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📝 Submit Assignment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            padding: 40px;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        h2 { text-align: center; color: #333; }
        label { display: block; margin: 15px 0 5px; }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Submit Your Assignment</h2>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" required>

            <label for="assignment_file">Upload File (optional):</label>
            <input type="file" name="assignment_file">

            <button type="submit" >Submit Assignment</button>
            <button type="button" onclick="window.location.href='userDashboard.php'" >Back to Dashboard</button>
</body>
</html>
