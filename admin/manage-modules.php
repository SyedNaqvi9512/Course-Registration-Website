<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Add Module
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_module'])) {
    $name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $leaderID = intval($_POST['module_leader_id']);
    $description = mysqli_real_escape_string($conn, $_POST['module_description']);
    $image = isset($_POST['module_image']) ? mysqli_real_escape_string($conn, $_POST['module_image']) : null;

    $sql = "INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Image) 
            VALUES ('$name', $leaderID, '$description', " . ($image ? "'$image'" : "NULL") . ")";
    mysqli_query($conn, $sql);
}

// Delete Module
if (isset($_GET['delete_module'])) {
    $id = intval($_GET['delete_module']);

    // Step 1: Delete related records in ProgrammeModules
    $sqlDeleteProgrammeModules = "DELETE FROM ProgrammeModules WHERE ModuleID = $id";
    if (mysqli_query($conn, $sqlDeleteProgrammeModules)) {
        // Step 2: Delete the module from Modules
        $sqlDeleteModule = "DELETE FROM Modules WHERE ModuleID = $id";
        if (mysqli_query($conn, $sqlDeleteModule)) {
            header("Location: manage-modules.php");
            exit();
        } else {
            echo "Error deleting module: " . mysqli_error($conn);
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
    <title>Manage Modules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-light">
        <h1>Manage Modules</h1>

        <!-- Add Module Form -->
        <form method="POST" class="mb-4">
            <h3>Add New Module</h3>
            <div class="mb-3">
                <label for="module_name" class="form-label">Module Name</label>
                <input type="text" class="form-control" id="module_name" name="module_name" required>
            </div>
            <div class="mb-3">
                <label for="module_leader_id" class="form-label">Module Leader</label>
                <select class="form-control" id="module_leader_id" name="module_leader_id" required>
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
                <label for="module_description" class="form-label">Description</label>
                <textarea class="form-control" id="module_description" name="module_description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="module_image" class="form-label">Image URL (Optional)</label>
                <input type="text" class="form-control" id="module_image" name="module_image">
            </div>
            <button type="submit" name="add_module" class="btn btn-success">Add Module</button>
        </form>
        <a href="dashborad.php" class="btn btn-success mb-3">Back to Dashboard</a>

        <!-- Module List -->
        <h3>Module List</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Leader</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT Modules.*, Staff.Name AS LeaderName 
                        FROM Modules 
                        LEFT JOIN Staff ON Modules.ModuleLeaderID = Staff.StaffID";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['ModuleID']}</td>
                            <td>{$row['ModuleName']}</td>
                            <td>{$row['LeaderName']}</td>
                            <td>{$row['Description']}</td>
                            <td>" . ($row['Image'] ? "<img src='{$row['Image']}' width='100'>" : "No Image") . "</td>
                            <td>
                                <a href='edit-module.php?id={$row['ModuleID']}' class='btn btn-warning'>Edit</a>
                                <a href='manage-modules.php?delete_module={$row['ModuleID']}' class='btn btn-danger'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>