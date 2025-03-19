<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

// Fetch program details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM Programmes WHERE ProgrammeID = $id";
    $result = mysqli_query($conn, $sql);
    $program = mysqli_fetch_assoc($result);
}

// Update Program
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_program'])) {
    $id = intval($_POST['program_id']);
    $name = mysqli_real_escape_string($conn, $_POST['program_name']);
    $levelID = intval($_POST['level_id']);
    $leaderID = intval($_POST['programme_leader_id']);
    $description = mysqli_real_escape_string($conn, $_POST['program_description']);
    $image = isset($_POST['program_image']) ? mysqli_real_escape_string($conn, $_POST['program_image']) : null;

    $sql = "UPDATE Programmes 
            SET ProgrammeName = '$name', LevelID = $levelID, ProgrammeLeaderID = $leaderID, 
                Description = '$description', Image = " . ($image ? "'$image'" : "NULL") . "
            WHERE ProgrammeID = $id";
    mysqli_query($conn, $sql);
    header("Location: manage-programs.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Program</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style2.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
        <div class="col-6">
        <h1 class="text-light">Edit Program</h1>
        </div>
        <div class="col-6">
        
        </div>
</div>
        <!-- Edit Program Form -->
        <form method="POST">
            <input type="hidden" name="program_id" value="<?php echo $program['ProgrammeID']; ?>">
            <div class="mb-3">
                <label for="program_name" class="form-label text-light">Program Name</label>
                <input type="text" class="form-control" id="program_name" name="program_name" value="<?php echo $program['ProgrammeName']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="level_id" class="form-label text-light">Level</label>
                <select class="form-control" id="level_id" name="level_id" required>
                    <?php
                    $sql = "SELECT * FROM Levels";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['LevelID'] == $program['LevelID']) ? 'selected' : '';
                        echo "<option value='{$row['LevelID']}' $selected>{$row['LevelName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="programme_leader_id" class="form-label text-light">Program Leader</label>
                <select class="form-control" id="programme_leader_id" name="programme_leader_id" required>
                    <?php
                    $sql = "SELECT * FROM Staff";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($row['StaffID'] == $program['ProgrammeLeaderID']) ? 'selected' : '';
                        echo "<option value='{$row['StaffID']}' $selected>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="program_description" class="form-label text-light">Description</label>
                <textarea class="form-control" id="program_description" name="program_description" required><?php echo $program['Description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="program_image" class="form-label text-light">Image URL (Optional)</label>
                <input type="text" class="form-control" id="program_image" name="program_image" value="<?php echo $program['Image']; ?>">
            </div>
            <button type="submit" name="update_program" class="btn btn-success mb-4">Update Program</button>
          
        </form>
        <a href="manage-programs.php" class="btn btn-success mb-3">Back to Manage Programs</a>
    </div>
</body>
</html>