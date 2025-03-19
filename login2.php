<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'student_course_hub');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate credentials
    $stmt = $conn->prepare("SELECT StaffID, Name FROM Staff WHERE StaffID = ? AND password = SHA2(?, 256)");
    $stmt->bind_param("is", $staff_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['staff_id'] = $row['StaffID'];
        $_SESSION['staff_name'] = $row['Name'];
        header("Location: staff/dashboard.php");
        exit();
    } else {
        $error = "Invalid Staff ID or Password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Staff Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="staff_id" class="form-label">Staff ID</label>
                                <input type="number" class="form-control" id="staff_id" name="staff_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>