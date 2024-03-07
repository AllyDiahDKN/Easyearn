<?php
// Include your database connection file
require_once '../db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $permissionType = $_POST['permission_type'];

    // Hash the password (you should use a more secure method)
    $hashedPassword = md5($password);

    // Query to insert data into the admin table
    $insertQuery = "INSERT INTO admin (first_name, last_name, email, password, permission_type, activation)
                    VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$permissionType', 'active')";
    $result = $conn->query($insertQuery);

    if ($result === TRUE) {
        // Admin created successfully
        echo '<script>alert("Admin created successfully"); window.location.href = "admin-list.php";</script>';
        exit();
    } else {
        // Error inserting data
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
        exit();
    }
} else {
    // Invalid request method
    echo "Invalid request method";
    exit();
}
?>