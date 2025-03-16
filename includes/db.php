<?php

// Database host
$host = 'localhost';       
// Database username   
$user = 'root';     
// Database password          
$password = '';      
// Database name         
$database = 'student_course_hub'; 

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>