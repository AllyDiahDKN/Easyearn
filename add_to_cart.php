<?php
// Include your database connection file
include('db.php');

// Start the session (if not already started)
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve values from the form
    $productId = $_POST['product_id'];
    $selectedSize = $_POST['selected_size'];
    $quantity = $_POST['ec_qtybtn'];
    $price = $_POST['price'];

    // Validate and sanitize inputs (you should customize this based on your needs)
    $productId = filter_var($productId, FILTER_SANITIZE_NUMBER_INT);
    $selectedSize = filter_var($selectedSize, FILTER_SANITIZE_STRING);
    $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);

    // Get user_id from the session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if user is logged in
    if (!$userId) {
        // Redirect to the login page or handle the situation as needed
        header("Location: login.php");
        exit;
    }

    // Check if the product already exists in the cart for the user with status "In Cart"
    $existingCartItemQuery = "SELECT cart_id, quantity FROM cart WHERE user_id = $userId AND product_id = $productId AND (size_id = '$selectedSize' OR size_id = '0') AND status = 'In Cart'";
    $existingCartItemResult = $conn->query($existingCartItemQuery);

    if ($existingCartItemResult && $existingCartItemResult->num_rows > 0) {
        // Product already exists in the cart, update the quantity
        $existingCartItemData = $existingCartItemResult->fetch_assoc();
        $cartId = $existingCartItemData['cart_id'];
        $existingQuantity = $existingCartItemData['quantity'];

        // Calculate the new quantity
        $newQuantity = $existingQuantity + $quantity;

        // Update the quantity in the cart
        $updateQuantityQuery = "UPDATE cart SET quantity = $newQuantity WHERE cart_id = $cartId";
        $updateResult = $conn->query($updateQuantityQuery);

        if ($updateResult) {
            // Successfully updated quantity in cart
            // Redirect back to the previous page or any other page
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } else {
            // Handle error, e.g., display an error message
            echo "Error updating quantity in cart: " . $conn->error;
        }
    } else {
        // Product does not exist in the cart, insert a new row
        // Example SQL query to insert into the cart table
        $insertQuery = "INSERT INTO cart (user_id, product_id, size_id, quantity, status, price) VALUES ($userId, $productId, '$selectedSize', $quantity, 'In Cart', $price)";

        // Execute the query
        $result = $conn->query($insertQuery);

        if ($result) {
            // Successfully added to cart
            // Redirect back to the previous page or any other page
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } else {
            // Handle error, e.g., display an error message
            echo "Error adding to cart: " . $conn->error;
        }
    }
} else {
    // If the form is not submitted, redirect to the home page or any other page
    header("Location: index.php");
    exit;
}
?>
