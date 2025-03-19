<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Add Program
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_program'])) {
    $name = mysqli_real_escape_string($conn, $_POST['program_name']);
    $levelID = intval($_POST['level_id']);
    $leaderID = intval($_POST['programme_leader_id']);
    $description = mysqli_real_escape_string($conn, $_POST['program_description']);
    $image = isset($_POST['program_image']) ? mysqli_real_escape_string($conn, $_POST['program_image']) : null;

    $sql = "INSERT INTO Programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image) 
            VALUES ('$name', $levelID, $leaderID, '$description', " . ($image ? "'$image'" : "NULL") . ")";
    mysqli_query($conn, $sql);
}

// Delete Program
if (isset($_GET['delete_program'])) {
    $id = intval($_GET['delete_program']);

    // Step 1: Delete related records in ProgrammeModules
    $sqlDeleteProgrammeModules = "DELETE FROM ProgrammeModules WHERE ProgrammeID = $id";
    if (mysqli_query($conn, $sqlDeleteProgrammeModules)) {
        // Step 2: Delete the program from Programmes
        $sqlDeleteProgram = "DELETE FROM Programmes WHERE ProgrammeID = $id";
        if (mysqli_query($conn, $sqlDeleteProgram)) {
            header("Location: manage-programs.php");
            exit();
        } else {
            echo "Error deleting program: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting related records: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-light">
        <h1>Manage Programs</h1>
        <form method="POST" class="mb-4">
            <h3>Add New Program</h3>
            <div class="mb-3">
                <label for="program_name" class="form-label">Program Name</label>
                <input type="text" class="form-control" id="program_name" name="program_name" required>
            </div>
            <div class="mb-3">
                <label for="level_id" class="form-label">Level</label>
                <select class="form-control" id="level_id" name="level_id" required>
                    <?php
                    $sql = "SELECT * FROM Levels";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['LevelID']}'>{$row['LevelName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="programme_leader_id" class="form-label">Program Leader</label>
                <select class="form-control" id="programme_leader_id" name="programme_leader_id" required>
                    <?php
                    $sql = "SELECT * FROM Staff";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['StaffID']}'>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="program_description" class="form-label">Description</label>
                <textarea class="form-control" id="program_description" name="program_description" required></textarea>
            </div>
            
            <button type="submit" name="add_program" class="btn btn-success">Add Program</button>
        </form>
        <a href="dashborad.php" class="btn btn-success mb-3">Back to Dashboard</a>

        <h3 class="text-light">Program List</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>Leader</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT Programmes.*, Levels.LevelName, Staff.Name AS LeaderName 
                        FROM Programmes 
                        LEFT JOIN Levels ON Programmes.LevelID = Levels.LevelID 
                        LEFT JOIN Staff ON Programmes.ProgrammeLeaderID = Staff.StaffID";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['ProgrammeID']}</td>
                            <td>{$row['ProgrammeName']}</td>
                            <td>{$row['LevelName']}</td>
                            <td>{$row['LeaderName']}</td>
                            <td>{$row['Description']}</td>
                            <td>" . ($row['Image'] ? "<img src='{$row['Image']}' width='100'>" : "No Image") . "</td>
                            <td>
                                <a href='edit-program.php?id={$row['ProgrammeID']}' class='btn btn-warning'>Edit</a>
                                <a href='manage-programs.php?delete_program={$row['ProgrammeID']}' class='btn btn-danger'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>