<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include the database connection file
include '../includes/db.php';

// Fetch student data
$sql = "SELECT interestedstudents.*, Programmes.ProgrammeName 
        FROM interestedstudents 
        LEFT JOIN Programmes ON interestedstudents.ProgrammeID = Programmes.ProgrammeID";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="interested_students.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Name', 'Email', 'Programme', 'Registered At']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['InterestID'],
        $row['StudentName'],
        $row['Email'],
        $row['ProgrammeName'],
        $row['RegisteredAt']
    ]);
}

fclose($output);

exit();
?>