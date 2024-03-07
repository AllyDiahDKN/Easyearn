<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle the situation as needed
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Check if the mobile number starts with '+'
    if (substr($mobile, 0, 1) !== '+') {
        echo "Mobile number must start with '+' sign.";
        exit; // Stop further execution
    }

    // Check if the customer is already registered based on mobile number and user ID
    $checkCustomerQuery = "SELECT id FROM customers WHERE mobile = ? AND user_id = ?";
    $stmt = $conn->prepare($checkCustomerQuery);
    $stmt->bind_param("si", $mobile, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Customer already registered
        echo "Customer with mobile number $mobile is already registered.";
    } else {
        // Customer not registered, proceed with insertion
        // Prepare and execute the SQL statement to insert data into the customers table
        $insertQuery = "INSERT INTO customers (user_id, first_name, last_name, mobile, email, address, city, country)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isssssss", $userId, $firstName, $lastName, $mobile, $email, $address, $city, $country);

        if ($stmt->execute()) {
            // Customer details inserted successfully
            echo "Customer details inserted successfully.";

            // Retrieve the customer ID
            $customerId = $stmt->insert_id;
    
            // Output or use the customer ID as needed
            echo "Customer ID: " . $customerId;

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
            // Error inserting data
            echo "Error inserting customer details: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>
