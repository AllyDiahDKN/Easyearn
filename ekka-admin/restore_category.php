<?php
// Include your database connection file here
require_once '../db.php';

// Check if category_id is provided in the URL
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Retrieve category data before deletion
    $sql = "SELECT * FROM deleted_category WHERE category_id = '$category_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch category data
        $categoryData = $result->fetch_assoc();

        // Insert category data into category table
        $insertQuery = "INSERT INTO category (category_id, category_name, category_image, active, popular)
                        VALUES ('{$categoryData['category_id']}', '{$categoryData['category_name']}', '{$categoryData['category_image']}', '{$categoryData['active']}', '{$categoryData['popular']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the category from the deleted_category table
            $deleteQuery = "DELETE FROM deleted_category WHERE category_id = '$category_id'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting category: " . $conn->error;
            }
        } else {
            echo "Error inserting into category table: " . $conn->error;
        }
    } else {
        echo "Category not found.";
    }
} else {
    echo "Category ID not provided.";
}
?>
