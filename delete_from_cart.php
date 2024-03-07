<?php
// Include your database connection file
include('db.php');

// Check if cart_id is provided
if (isset($_GET['cart_id'])) {
    // Retrieve cart_id from the URL
    $cartId = $_GET['cart_id'];

    // Validate and sanitize the cart_id
    $cartId = filter_var($cartId, FILTER_SANITIZE_NUMBER_INT);

    // Perform the delete operation
    $deleteQuery = "DELETE FROM cart WHERE cart_id = $cartId";
    $deleteResult = $conn->query($deleteQuery);

    // Check if the deletion was successful
    if ($deleteResult) {
        // Redirect back to the previous page
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    } else {
        // Handle the error, you can customize this part based on your needs
        echo "Error deleting item from cart: " . $conn->error;
    }
} else {
    // If cart_id is not provided in the URL, redirect to the home page or any other page
    header("Location: index.php");
    exit;
}
?>
