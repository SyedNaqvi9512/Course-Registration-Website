<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_course_hub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch modules led by the staff
$modules_stmt = $conn->prepare("SELECT ModuleID, ModuleName FROM Modules WHERE ModuleLeaderID = ?");
$modules_stmt->bind_param("i", $_SESSION['staff_id']);
$modules_stmt->execute();
$modules_result = $modules_stmt->get_result();

// Fetch programmes led by the staff
$programmes_stmt = $conn->prepare("SELECT ProgrammeID, ProgrammeName FROM Programmes WHERE ProgrammeLeaderID = ?");
$programmes_stmt->bind_param("i", $_SESSION['staff_id']);
$programmes_stmt->execute();
$programmes_result = $programmes_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style3.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">Staff Dashboard</a>
            <div class="navbar-nav">
                <a class="nav-link" href="my-modules.php">My Modules</a>
                <a class="nav-link" href="my-programmes.php">My Programmes</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-light">Welcome, <?php echo $_SESSION['staff_name']; ?></h2>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card  bg-dark text-light">
                    <div class="card-header">
                        <h5>My Modules</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php while ($module = $modules_result->fetch_assoc()): ?>
                                <li><?php echo $module['ModuleName']; ?></li>
                            <?php endwhile; ?>
                        </ul>
                        <a href="my-modules.php" class="btn btn-success">Go to my Modules</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-dark text-light ">
                    <div class="card-header">
                        <h5>My Programmes</h5>
                    </div>
                    <div class="card-body mb-4">
                        <ul>
                            <?php while ($programme = $programmes_result->fetch_assoc()): ?>
                                <li><?php echo $programme['ProgrammeName']; ?></li>
                            <?php endwhile; ?>
                        </ul>
                        <a href="my-programmes.php" class="btn btn-success">Go to My Programmes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$modules_stmt->close();
$programmes_stmt->close();
$conn->close();
?>