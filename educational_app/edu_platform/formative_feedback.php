<?php
session_start();
require_once "config.php";

// Only allow teachers/admins
if (!isset($_SESSION["loggedin"]) || ($_SESSION["user_type"] !== 'teacher' && $_SESSION["user_type"] !== 'admin')) {
    header("location: login.php");
    exit;
}

// Fetch students
$students = [];
$result = $link->query("SELECT id, username FROM users ORDER BY username");
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Formative Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            padding: 40px;
        }

        .feedback-form-container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .feedback-form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="feedback-form-container">
    <h2>📩 Formative Feedback</h2>
    <form method="post" action="submit_feedback.php">
        <label for="student_id">Student:</label>
        <select name="student_id" id="student_id" required>
            <option value="">-- Select Student --</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['username']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="academic_year">Academic Year:</label>
        <select name="academic_year" id="academic_year" required>
            <option value="">-- Select Year --</option>
            <option value="2023-2024">2023-2024</option>
            <option value="2024-2025">2024-2025</option>
        </select>

        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" placeholder="e.g., Mathematics" required>

        <label for="marks">Marks (0–100):</label>
        <input type="number" name="marks" id="marks" min="0" max="100" required>

        <label for="comments">Comments (optional):</label>
        <textarea name="comments" id="comments" rows="3" placeholder="Feedback or suggestions..."></textarea>

        <button type="submit">Submit Feedback</button>
            </br>
        <button type="button" onclick="window.location.href='adminDashboard.php'" class="btn btn-secondary">Back to Dashboard</button>
        <button type="button" onclick="window.location.href='home.php'" class="btn btn-primary">Go to Home</button>
        
    </form>
</div>

</body>
</html>
