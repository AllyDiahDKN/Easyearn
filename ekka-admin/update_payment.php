<?php
require_once '../db.php';
// Check if the status and order ID are set in the POST request
if (isset($_POST['status']) && isset($_POST['order_id'])) {
    $status = $_POST['status'];
    $orderId = $_POST['order_id'];

    // Update the approval status in the products table for the specific order ID
    $sql = "UPDATE orders SET payment = ? WHERE order_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    if ($stmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $conn->error;
    }
} else {
    echo "Status or order ID not provided.";
}
?>
