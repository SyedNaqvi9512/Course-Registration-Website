<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php"); 
    exit();
}

include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container p-4">
        <div class="text-center mb-4 text-light">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo $_SESSION['admin_email']; ?>!</p>
            
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-4 ">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Programs</h5>
                        <p class="card-text">Add, edit, or delete programs.</p>
                        <a href="manage-programs.php" class="btn btn-success">Go to Manage Programs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Modules</h5>
                        <p class="card-text">Add, edit, or delete modules.</p>
                        <a href="manage-modules.php" class="btn btn-success">Go to Manage Modules</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">View Student Interests</h5>
                        <p class="card-text">See a list of students who have registered interest in programs.</p>
                        <a href="view-students.php" class="btn btn-success">Go to View Students</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Export Mailing Lists</h5>
                        <p class="card-text">Download CSV file of interested students</p>
                        <a href="export-students.php" class="btn btn-success">Export Mailing List</a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-1">
            <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
            </div>
            
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>