<?php
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
    <title>View Student Interests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-light">View Student Interests</h1>
        <a href="dashborad.php" class="btn btn-success mb-3">Back to Dashboard</a>
        <a href="export-students.php" class="btn btn-success mb-3">Export as CSV</a>

        <table class="table table-bordered p-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Programme</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT interestedstudents.*, Programmes.ProgrammeName 
                        FROM interestedstudents 
                        LEFT JOIN Programmes ON interestedstudents.ProgrammeID = Programmes.ProgrammeID";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['InterestID']}</td>
                            <td>{$row['StudentName']}</td>
                            <td>{$row['Email']}</td>
                            <td>{$row['ProgrammeName']}</td>
                            <td>{$row['RegisteredAt']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>