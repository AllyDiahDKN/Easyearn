<?php
// Include your database connection file here
require_once '../db.php';

// Check if customer id is provided in the URL
if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Retrieve customer data before deletion
    $sql = "SELECT * FROM deleted_customers WHERE id = '$customer_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch customer data
        $customerData = $result->fetch_assoc();

        // Insert customer data into customer table
        $insertQuery = "INSERT INTO customers (first_name, last_name, id, email, city, country, mobile, address, user_id, house_number, date_created, time_created)
                        VALUES ('{$customerData['first_name']}', '{$customerData['last_name']}', '{$customerData['id']}', '{$customerData['email']}', '{$customerData['city']}', '{$customerData['country']}', '{$customerData['mobile']}', '{$customerData['address']}', '{$customerData['user_id']}', '{$customerData['house_number']}', '{$customerData['date_created']}', '{$customerData['time_created']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the customer from the deleted_customer table
            $deleteQuery = "DELETE FROM deleted_customers WHERE id = '$customer_id'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting customer: " . $conn->error;
            }
        } else {
            echo "Error inserting into customer table: " . $conn->error;
        }
    } else {
        echo "Customer not found.";
    }
} else {
    echo "Customer ID not provided.";
}
?>
