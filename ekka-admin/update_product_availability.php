<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the product ID is set
    if (isset($_POST['product_id'])) {
        // Sanitize and validate input data
        $productId = $_POST['product_id'];

        // Toggle the availability status in the database
        $sql = "UPDATE products SET availability = IF(availability = 1, 0, 1) WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            // Return a success message
            echo 'Product availability updated successfully.';
        } else {
            // Return an error message
            echo 'Failed to update product availability.';
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Return an error message if the product ID is not set
        echo 'Product ID is missing.';
    }
} else {
    // Return an error message for invalid request method
    echo 'Invalid request method.';
}
?>
