<?php
session_start();
require_once "config.php";

// Only allow teachers/admins
if (!isset($_SESSION["loggedin"]) || ($_SESSION["user_type"] !== 'teacher' && $_SESSION["user_type"] !== 'admin')) {
    header("location: login.php");
    exit;
}

// Initialize status variables
$success = null;
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'] ?? '';
    $academic_year = $_POST['academic_year'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $marks = $_POST['marks'] ?? '';
    $comments = $_POST['comments'] ?? null;

    if (!empty($student_id) && !empty($academic_year) && !empty($subject) && is_numeric($marks)) {
        $stmt = $link->prepare("INSERT INTO student_feedback (student_id, academic_year, subject, marks, comments) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issis", $student_id, $academic_year, $subject, $marks, $comments);
            $success = $stmt->execute();
            if (!$success) {
                $error = $stmt->error;
            }
            $stmt->close();
        } else {
            $success = false;
            $error = $link->error;
        }
    } else {
        $success = false;
        $error = "Invalid or missing form data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Submitted</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
            text-align: center;
        }

        .message-box {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .success {
            color: #28a745;
            font-size: 18px;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
            font-size: 18px;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="message-box">
    <?php if ($success === true): ?>
        <p class="success">✅ Feedback submitted successfully!</p>
    <?php elseif ($success === false): ?>
        <p class="error">❌ Failed to submit feedback. <?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <p class="error">⚠️ No form data submitted.</p>
    <?php endif; ?>
    <a href="formative_feedback.php" class="btn-back">← Back to Feedback</a>
</div>

</body>
</html>
