<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Fetch module details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM Modules WHERE ModuleID = $id";
    $result = mysqli_query($conn, $sql);
    $module = mysqli_fetch_assoc($result);
}

// Update Module
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_module'])) {
    $id = intval($_POST['module_id']);
    $name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $leaderID = intval($_POST['module_leader_id']);
    $description = mysqli_real_escape_string($conn, $_POST['module_description']);
    $image = isset($_POST['module_image']) ? mysqli_real_escape_string($conn, $_POST['module_image']) : null;

    $sql = "UPDATE Modules 
            SET ModuleName = '$name', ModuleLeaderID = $leaderID, Description = '$description', 
                Image = " . ($image ? "'$image'" : "NULL") . "
            WHERE ModuleID = $id";
    mysqli_query($conn, $sql);
    header("Location: manage-modules.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-light">
        <div class="row">
            <div class="col-6">
            <h1>Edit Module</h1>
            </div>
            <div class="col-6">
            
            </div>
        </div>
        
        <form method="POST">
            <input type="hidden" name="module_id" value="<?php echo $module['ModuleID']; ?>">
            <div class="mb-3">
                <label for="module_name" class="form-label">Module Name</label>
                <input type="text" class="form-control" id="module_name" name="module_name" value="<?php echo $module['ModuleName']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="module_leader_id" class="form-label">Module Leader</label>
                <select class="form-control" id="module_leader_id" name="module_leader_id" required>
                    <?php
                    $sql = "SELECT * FROM Staff";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['StaffID'] == $module['ModuleLeaderID']) ? 'selected' : '';
                        echo "<option value='{$row['StaffID']}' $selected>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="module_description" class="form-label">Description</label>
                <textarea class="form-control" id="module_description" name="module_description" required><?php echo $module['Description']; ?></textarea>
            </div>
            
            <button type="submit" name="update_module" class="btn btn-success mb-4">Update Module</button>
        </form>
        <a href="manage-modules.php" class="btn btn-success mb-3">Back to Manage Modules</a>
    </div>
</body>
</html>