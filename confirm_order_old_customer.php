<?php
// Include your database connection file
include('db.php');

// Start the session (if not already started)
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve values from the form
    $customerId = $_POST['ec_select_country']; // Assuming you are getting customer ID from the form

    // Validate and sanitize inputs (you should customize this based on your needs)
    $customerId = filter_var($customerId, FILTER_SANITIZE_NUMBER_INT);

    // Get user_id from the session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if user is logged in
    if (!$userId) {
        // Redirect to the login page or handle the situation as needed
        header("Location: login.php");
        exit;
    }

    // Perform database operations to add the order
    // You need to implement this part based on your database schema and structure
    // For example, you might have an "orders" table to store order information
    // This is just a placeholder, replace it with your actual logic

    // Example SQL query to calculate total price of products in the cart
    $totalPriceQuery = "SELECT SUM(quantity * price) AS total_price
                        FROM cart
                        WHERE user_id = $userId AND status = 'In Cart'";

    // Execute the query
    $totalPriceResult = $conn->query($totalPriceQuery);

    if ($totalPriceResult) {
        // Fetch the total price from the result
        $row = $totalPriceResult->fetch_assoc();
        $totalPrice = $row['total_price'];

        // Example SQL query to insert into the orders table with total
        $insertOrderQuery = "INSERT INTO orders (user_id, customer_id, total) 
                            VALUES ($userId, $customerId, $totalPrice)";

        // Execute the query
        $insertOrderResult = $conn->query($insertOrderQuery);

        if ($insertOrderResult) {
            // Order successfully inserted

            // Get the auto-generated order ID
            $orderId = $conn->insert_id;

            // Update the cart with the order_id and change status to 'ordered'
            $updateCartQuery = "UPDATE cart SET order_id = $orderId, status = 'ordered' 
                                WHERE user_id = $userId AND status = 'In Cart'";
            $updateCartResult = $conn->query($updateCartQuery);

            if ($updateCartResult) {
                // Cart successfully updated
                echo "Order confirmed for the selected customer. Cart updated!";
                   // Redirect to the order_invoice.php page with the order number
    header("Location: order_invoice.php?order_number=$orderId");
    exit; // Ensure no further output is sent
            } else {
                // Handle the error
                echo "Error updating cart: " . $conn->error;
            }
        } else {
            // Handle the error
            echo "Error confirming order: " . $conn->error;
        }
    } else {
        // Handle the error
        echo "Error calculating total price: " . $conn->error;
    }
} else {
    // If the form is not submitted, redirect to the home page or any other page
    header("Location: index.php");
    exit;
}
?>