<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id"])) {
    $student_id = $_SESSION["id"];
    $question = trim($_POST["question"]);

    if (!empty($question)) {
        $stmt = $link->prepare("INSERT INTO formative_feedback (student_id, question) VALUES (?, ?)");
        $stmt->bind_param("is", $student_id, $question);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>💬 Discussion Forum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f8fb;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 650px;
            margin: 60px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        textarea {
            width: 100%;
            max-width: 550px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            font-size: 16px;
        }
        button {
            margin-top: 15px;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #0069d9;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #004c9b;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>💬 Ask a Question</h2>
    <form method="post">
        <textarea name="question" rows="5" placeholder="Type your question here..." required></textarea>
        <button type="submit">Submit Question</button>
        <button type="button" onclick="window.location.href='userDashboard.php'" >Back to Dashboard</button>
    </form>
</div>
</body>
</html>
