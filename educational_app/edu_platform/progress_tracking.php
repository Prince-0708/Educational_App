<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}

require_once "config.php";

// Sample SQL: adjust table/column names to match your database schema
$sql = "SELECT u.username, a.title, s.submitted_at, s.grade
        FROM submissions s
        JOIN users u ON s.user_id = u.id
        JOIN assignments a ON s.assignment_id = a.id
        ORDER BY s.submitted_at DESC";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Progress Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
        }

        .table th {
            background-color: #343a40;
            color: #fff;
        }

        h2 {
            margin-bottom: 30px;
            font-weight: 600;
            color: #343a40;
        }

        .back-btn {
            margin-top: 20px;
        }

        .grade-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .grade-A { background-color: #28a745; color: white; }
        .grade-B { background-color: #17a2b8; color: white; }
        .grade-C { background-color: #ffc107; color: black; }
        .grade-F { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Student Progress Tracker</h2>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Assignment</th>
                        <th>Submitted On</th>
                        <th>Grade</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                                $grade = $row['grade'];
                                $badge_class = "grade-F";
                                if ($grade >= 90) $badge_class = "grade-A";
                                elseif ($grade >= 75) $badge_class = "grade-B";
                                elseif ($grade >= 60) $badge_class = "grade-C";

                                $progress = $grade . "%";
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= date("M d, Y", strtotime($row['submitted_at'])) ?></td>
                                <td><span class="badge <?= $badge_class ?> grade-badge"><?= $grade ?></span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= $grade ?>%;" aria-valuenow="<?= $grade ?>" aria-valuemin="0" aria-valuemax="100"><?= $progress ?></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No submissions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center back-btn">
        <a href="adminDashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>


</div>
</body>
</html>
