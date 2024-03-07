<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Check if the form is submitted
if (isset($_POST['save_category'])) {
    // Retrieve category name from the form data
    $categoryName = $_POST['category_name'];

    // Insert category name into the category table
    $sql = "INSERT INTO category (category_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoryName);

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
