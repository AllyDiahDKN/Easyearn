<?php
// Include your database connection file here
require_once '../db.php';

// Check if product_id is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Retrieve product data before deletion
    $sql = "SELECT * FROM deleted_products WHERE product_id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch product data
        $productData = $result->fetch_assoc();

        // Insert product data into products table
        $insertQuery = "INSERT INTO products (product_id, code, product_name, product_image, description, rating, price, wholesale_price, stock_id, stock, availability, home, category_id, sub_category_id, date_added, image_url, color, size_id, brand_id)
                        VALUES ('{$productData['product_id']}', '{$productData['code']}', '{$productData['product_name']}', '{$productData['product_image']}', '{$productData['description']}', '{$productData['rating']}', '{$productData['price']}', '{$productData['wholesale_price']}', '{$productData['stock_id']}', '{$productData['stock']}', '{$productData['availability']}', '{$productData['home']}', '{$productData['category_id']}', '{$productData['sub_category_id']}', '{$productData['date_added']}', '{$productData['image_url']}', '{$productData['color']}', '{$productData['size_id']}', '{$productData['brand_id']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the product from the deleted_products table
            $deleteQuery = "DELETE FROM deleted_products WHERE product_id = '$product_id'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting product: " . $conn->error;
            }
        } else {
            echo "Error inserting into products table: " . $conn->error;
        }
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID not provided.";
}
?>
