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

// Fetch programme details
$stmt = $conn->prepare("
    SELECT DISTINCT p.ProgrammeName, p.Description
    FROM Programmes p
    JOIN ProgrammeModules pm ON p.ProgrammeID = pm.ProgrammeID
    JOIN Modules m ON pm.ModuleID = m.ModuleID
    WHERE m.ModuleLeaderID = ?
");
$stmt->bind_param("i", $_SESSION['staff_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Programmes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style3.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">My Programmes</a>
            <div class="navbar-nav">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="my-modules.php">My Modules</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 text-light">
        <h2>My Programmes</h2>
        <p>Here are the programmes that include the modules you teach:</p>
        <table class="table table-bordered bg-primary">
            <thead>
                <tr>
                    <th>Programme Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ProgrammeName']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>