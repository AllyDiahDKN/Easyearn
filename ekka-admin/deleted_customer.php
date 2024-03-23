<?php
// Include your database connection file here
require_once '../db.php';

// Check if customer ID is provided in the URL
if (isset($_GET['id'])) {
    $customerId = $_GET['id'];

    // Prepare and bind parameters
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customerId);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch customer data
        $customerData = $result->fetch_assoc();

      // Insert customer data into deleted_customers table
        $stmt = $conn->prepare("INSERT INTO deleted_customers (id, first_name, last_name, email, city, country, mobile, address, user_id, house_number, date_created, time_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssisss", $customerData['id'], $customerData['first_name'], $customerData['last_name'], $customerData['email'], $customerData['city'], $customerData['country'], $customerData['mobile'], $customerData['address'], $customerData['user_id'], $customerData['house_number'], $customerData['date_created'], $customerData['time_created']);

        if ($stmt->execute()) {
            // Delete the customer from the customer table
            $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
            $stmt->bind_param("i", $customerId);
            if ($stmt->execute()) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting customer: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_customers table: " . $conn->error;
        }
    } else {
        echo "customer not found.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Customer ID not provided.";
}

// Close connection
$conn->close();
?>
