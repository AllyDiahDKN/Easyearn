<?php
// Include your database connection file here
require_once '../db.php';

// Check if category ID is provided in the URL
if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];

    // Prepare and bind parameters
    $stmt = $conn->prepare("SELECT * FROM category WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch category data
        $categoryData = $result->fetch_assoc();

        // Insert category data into deleted_category table
        $stmt = $conn->prepare("INSERT INTO deleted_category (category_id, category_name, category_image, active, popular) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $categoryData['category_id'], $categoryData['category_name'], $categoryData['category_image'], $categoryData['active'], $categoryData['popular']);

        if ($stmt->execute()) {
            // Delete the category from the categories table
            $stmt = $conn->prepare("DELETE FROM category WHERE category_id = ?");
            $stmt->bind_param("i", $categoryId);
            if ($stmt->execute()) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting category: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_category table: " . $conn->error;
        }
    } else {
        echo "Category not found.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Category ID not provided.";
}

// Close connection
$conn->close();
?>

