<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_course_hub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$programmes = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        // Fetch programmes based on email
        $email = $_POST['email'];

        $stmt = $conn->prepare("
            SELECT i.InterestID, p.ProgrammeName, p.ProgrammeID
            FROM InterestedStudents i
            JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
            WHERE i.Email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $programmes = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $message = "No programmes found for the provided email address.";
        }

        $stmt->close();
    } elseif (isset($_POST['withdraw'])) {
        // Withdraw interest from a selected programme
        $interest_id = $_POST['interest_id'];

        $delete_stmt = $conn->prepare("DELETE FROM InterestedStudents WHERE InterestID = ?");
        $delete_stmt->bind_param("i", $interest_id);
        if ($delete_stmt->execute()) {
            $message = "Your interest in the programme has been successfully withdrawn.";
        } else {
            $message = "An error occurred while withdrawing your interest. Please try again.";
        }
        $delete_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Interest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Withdraw Interest</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Return Dashboard</a>
                            </div>
        </div>
    </nav>

    <div class="container mt-5 text-light">
        <h2>Withdraw Interest in a Programme</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Step 1: Enter Email Address -->
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-success">View Programmes</button>
        </form>

        <!-- Display Programmes and Withdraw Option -->
        <?php if (!empty($programmes)): ?>
            <h3>Your Registered Programmes</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Programme Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programmes as $programme): ?>
                        <tr>
                            <td><?php echo $programme['ProgrammeName']; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="interest_id" value="<?php echo $programme['InterestID']; ?>">
                                    <button type="submit" name="withdraw" class="btn btn-danger">Withdraw Interest</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>