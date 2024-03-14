<?php
// Include your database connection file
require_once '../db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = $_POST['user_id'];
    $payment = $_POST['payment'];
    $issuedBy = $_POST['issued_by'];
    $referenceNumber = $_POST['reference_number'];
    $details = $_POST['details'];

    // Query to insert data into the commission table
    $insertQuery = "INSERT INTO commission (user_id, payment, issued_by, reference_number, details)
                    VALUES ('$userId', '$payment', '$issuedBy', '$referenceNumber', '$details')";
    $result = $conn->query($insertQuery);

    if ($result === TRUE) {
        // Commission entry created successfully
        echo '<script>alert("Commission entry created successfully"); window.location.href = "commission.php";</script>';
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
