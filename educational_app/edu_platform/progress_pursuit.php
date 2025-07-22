<?php
session_start();
require_once "config.php";

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$student_id = $_SESSION["id"];
$student_name = $_SESSION["username"] ?? "Student";

// Default filter values
$filter_subject = $_GET['subject'] ?? '';
$filter_year = $_GET['year'] ?? '';

$marks = $dates = [];

$sql = "SELECT subject, marks, created_at, academic_year FROM student_feedback WHERE student_id = ?";
$params = [$student_id];
$types = "i";

// Add filter conditions
if (!empty($filter_subject)) {
    $sql .= " AND subject = ?";
    $params[] = $filter_subject;
    $types .= "s";
}
if (!empty($filter_year)) {
    $sql .= " AND academic_year = ?";
    $params[] = $filter_year;
    $types .= "s";
}

$sql .= " ORDER BY created_at ASC";

$stmt = $link->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $marks[] = $row['marks'];
    $dates[] = date("M d", strtotime($row['created_at'])) . " ({$row['subject']})";
}
$stmt->close();

// For dropdowns
$subjects_result = $link->query("SELECT DISTINCT subject FROM student_feedback WHERE student_id = $student_id");
$years_result = $link->query("SELECT DISTINCT academic_year FROM student_feedback WHERE student_id = $student_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📈 My Progress</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f9ff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }
        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        select, button {
            padding: 8px;
            font-size: 14px;
        }
        canvas {
            display: block;
            margin: 0 auto;
        }
        .no-data {
            text-align: center;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📈 My Progress</h2>
    <h3>Welcome, <?= htmlspecialchars($student_name) ?></h3>

    <form method="GET">
        <select name="subject">
            <option value="">All Subjects</option>
            <?php while ($sub = $subjects_result->fetch_assoc()): ?>
                <option value="<?= $sub['subject'] ?>" <?= $filter_subject == $sub['subject'] ? 'selected' : '' ?>>
                    <?= $sub['subject'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="year">
            <option value="">All Years</option>
            <?php while ($yr = $years_result->fetch_assoc()): ?>
                <option value="<?= $yr['academic_year'] ?>" <?= $filter_year == $yr['academic_year'] ? 'selected' : '' ?>>
                    <?= $yr['academic_year'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <?php if (!empty($marks)): ?>
        <canvas id="progressChart" width="800" height="400"></canvas>
    <?php else: ?>
        <div class="no-data">No data available for the selected filters.</div>
    <?php endif; ?>
    <div class="text-center back-btn"><a href="userDashboard.php" class="btn btn-secondary">Back to Dashboard</a></div>
</div>


<?php if (!empty($marks)): ?>
<script>
    const ctx = document.getElementById('progressChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($dates) ?>,
            datasets: [{
                label: 'Marks',
                data: <?= json_encode($marks) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    title: { display: true, text: 'Marks (%)' }
                },
                x: {
                    title: { display: true, text: 'Date (Subject)' }
                }
            }
        }
    });
</script>
<?php endif; ?>
</body>
</html>
