<?php
// Include your database connection file here
require_once '../db.php';
// Check if product ID is provided in the URL
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Retrieve product data before deletion
    $sql = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch product data
        $productData = $result->fetch_assoc();

        // Insert product data into deleted_products table
        $insertQuery = "INSERT INTO deleted_products (product_image, product_id, product_name, category_id, brand_id, availability, price, stock, description, code)
                        VALUES ('{$productData['product_image']}', '{$productData['product_id']}', '{$productData['product_name']}', '{$productData['category_id']}', '{$productData['brand_id']}', '{$productData['availability']}', '{$productData['price']}', '{$productData['stock']}', '{$productData['description']}', '{$productData['code']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the product from the products table
            $deleteQuery = "DELETE FROM products WHERE product_id = '$productId'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting product: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_products table: " . $conn->error;
        }
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID not provided.";
}
?>
