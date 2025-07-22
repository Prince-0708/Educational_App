<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}

// Include DB config
require_once "config.php";

// Initialize variables
$title = $description = $subject = $due_date = "";
$title_err = $description_err = $subject_err = $file_err = $upload_success = "";

$created_by = $_SESSION["username"]; // Admin username from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate title
    $title = trim($_POST["title"]);
    if (empty($title)) {
        $title_err = "Please enter a title.";
    }

    // Validate subject
    $subject = trim($_POST["subject"]);
    if (empty($subject)) {
        $subject_err = "Please enter a subject.";
    }

    // Validate description
    $description = trim($_POST["description"]);
    if (empty($description)) {
        $description_err = "Please enter a description.";
    }

    // Due date
    $due_date = $_POST["due_date"] ?? null;

    // Handle file upload
    if (isset($_FILES["assignment_file"]) && $_FILES["assignment_file"]["error"] === 0) {
        $upload_dir = "uploads/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = uniqid() . "_" . basename($_FILES["assignment_file"]["name"]);
        $target_file = $upload_dir . $filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

        if (!in_array($file_type, $allowed_types)) {
            $file_err = "Only PDF, DOC, DOCX, PPT files are allowed.";
        } else {
            if (move_uploaded_file($_FILES["assignment_file"]["tmp_name"], $target_file)) {
                // Insert into DB
                $sql = "INSERT INTO assignments (title, description, subject, due_date, file_path, created_by) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssss", $title, $description, $subject, $due_date, $target_file, $created_by);
                    if (mysqli_stmt_execute($stmt)) {
                        $upload_success = "Assignment added successfully!";
                        $title = $description = $subject = $due_date = "";
                    } else {
                        $file_err = "Database error.";
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                $file_err = "Failed to move uploaded file.";
            }
        }
    } else {
        $file_err = "Please upload a file.";
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Assignment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 700px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Assignment</h2>

    <?php 
    if (!empty($upload_success)) {
        echo '<div class="alert alert-success">' . $upload_success . '</div>';
    }
    if (!empty($file_err)) {
        echo '<div class="alert alert-danger">' . $file_err . '</div>';
    }
    ?>

    <form action="add_assignment.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Assignment Title</label>
            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($title); ?>">
            <div class="invalid-feedback"><?php echo $title_err; ?></div>
        </div>

        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control <?php echo (!empty($subject_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($subject); ?>">
            <div class="invalid-feedback"><?php echo $subject_err; ?></div>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?php echo htmlspecialchars($due_date); ?>">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($description); ?></textarea>
            <div class="invalid-feedback"><?php echo $description_err; ?></div>
        </div>

        <div class="form-group">
            <label>Upload File</label>
            <input type="file" name="assignment_file" class="form-control-file">
        </div>

        <div class="form-group">
            <label>Created By</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($created_by); ?>" disabled>
        </div>

        <input type="submit" class="btn btn-primary" value="Add Assignment">
        <a href="adminDashboard.php" class="btn btn-secondary ml-2">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
