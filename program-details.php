<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program_id = $_POST['program_id'];
    $student_name = mysqli_real_escape_string($conn, $_POST['name']);
    $student_email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($student_name) || empty($student_email) || empty($program_id)) {
        $error_message = "Please fill out all fields.";
    } elseif (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {

        $sql = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email) 
                VALUES ($program_id, '$student_name', '$student_email')";

        if (mysqli_query($conn, $sql)) {
            $success_message = "You have been registered Successfully!!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

if (isset($_GET['id'])) {
    $program_id = $_GET['id'];
} else {
    die("Program ID not specified.");
}

// Fetching program details
$sql = "SELECT * FROM Programmes WHERE ProgrammeID = $program_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$program = mysqli_fetch_assoc($result);

if (!$program) {
    die("Program not found.");
}

$sql = "SELECT Modules.ModuleID, Modules.ModuleName, Modules.Description, Modules.ModuleLeaderID, ProgrammeModules.Year 
        FROM ProgrammeModules 
        JOIN Modules ON ProgrammeModules.ModuleID = Modules.ModuleID 
        WHERE ProgrammeModules.ProgrammeID = $program_id 
        ORDER BY ProgrammeModules.Year";
$modules_result = mysqli_query($conn, $sql);

if (!$modules_result) {
    die("Error fetching modules: " . mysqli_error($conn));
}

$modules = mysqli_fetch_all($modules_result, MYSQLI_ASSOC);

// modules by year
$modules_by_year = [];
foreach ($modules as $module) {
    $year = $module['Year'];
    if (!isset($modules_by_year[$year])) {
        $modules_by_year[$year] = [];
    }
    $modules_by_year[$year][] = $module;
}

// Fetching program leader
$program_leader_id = $program['ProgrammeLeaderID'];
$sql = "SELECT Name FROM Staff WHERE StaffID = $program_leader_id";
$program_leader_result = mysqli_query($conn, $sql);

if (!$program_leader_result) {
    die("Error fetching program leader: " . mysqli_error($conn));
}

$program_leader = mysqli_fetch_assoc($program_leader_result);

// Fetching module leaders
$module_leaders = [];
foreach ($modules as $module) {
    if (isset($module['ModuleLeaderID'])) {
        $module_leader_id = $module['ModuleLeaderID'];
        if (!isset($module_leaders[$module_leader_id])) {
            $sql = "SELECT Name FROM Staff WHERE StaffID = $module_leader_id";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $module_leaders[$module_leader_id] = mysqli_fetch_assoc($result);
            } else {
                die("Error fetching module leader: " . mysqli_error($conn));
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $program['ProgrammeName']; ?> - Program Details</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="container">
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-light"><?php echo $program['ProgrammeName']; ?></h1>

        <div class="col-6 mx-auto">
            <div class="card mb-4 ">
                <div class="card-body">
                    <h5 class="card-title t">Program Description</h5>
                    <p class="card-text"><?php echo $program['Description']; ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php for ($year = 1; $year <= 3; $year++): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Year <?php echo $year; ?></h5>
                            <?php if (isset($modules_by_year[$year])): ?>
                                <ul>
                                    <?php foreach ($modules_by_year[$year] as $module): ?>
                                        <li>
                                            <strong><?php echo $module['ModuleName']; ?></strong>
                                            <p><?php echo $module['Description']; ?></p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No modules</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="row">
            <div class="col-6 mb-4">
                <div class="card mb-4 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Faculty Members</h5>
                        <ul>
                            <li><strong>Program Leader:</strong> <?php echo $program_leader['Name']; ?></li>
                            <?php foreach ($module_leaders as $leader): ?>
                                <li><strong>Module Leader:</strong> <?php echo $leader['Name']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-6 mb-4">
                <div class="card h-100 ">
                    <div class="card-body">
                        <h5 class="card-title">Register Your Interest</h5>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo $success_message; ?>
                            </div>
                        <?php elseif (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="program_id" value="<?php echo $program['ProgrammeID']; ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <button type="submit" class="btn btn-light">Register Interest</button>

                            <div class="mb-3 mt-4">
                                <a href="index.php" class="btn btn-light">Back to Programs</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>