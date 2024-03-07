<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Check if order ID is provided via GET parameter
if(isset($_GET['order_id'])) {
    // Retrieve order details from the database based on order ID
    $orderId = $_GET['order_id'];
    // Query to fetch order details
    $sql = "SELECT user.first_name AS user_first_name, user.mobile AS user_mobile,
                   customers.first_name AS customer_first_name, customers.mobile AS customer_mobile,
                   products.product_name, cart.quantity, orders.discount, orders.total
            FROM orders
            INNER JOIN user ON orders.user_id = user.user_id
            INNER JOIN customers ON orders.customer_id = customers.id
            INNER JOIN cart ON orders.order_number = cart.order_id
            INNER JOIN products ON cart.product_id = products.product_id
            WHERE orders.order_number = ?";
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if order exists
    if($result->num_rows > 0) {
        // Fetch order details
        $orderDetails = $result->fetch_assoc();
        // Include HTML receipt template
        include 'receipt_template.php';
    } else {
        echo "Order not found.";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Order ID not provided.";
}
?>
