<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Check if the form is submitted
if (isset($_POST['save_brand'])) {
    // Retrieve brand name from the form data
    $brandName = $_POST['brand_name'];

    // Insert brand name into the brand table
    $sql = "INSERT INTO brands (brand_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $brandName);

    if ($stmt->execute()) {
        // Insertion successful, redirect back to the previous page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    } else {
        // Insertion failed, display error message
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
