<?php
require_once '../db.php'; // Adjust the path as needed

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize customer details from the form
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $userId = intval($_POST['userId']);
    $houseNumber = mysqli_real_escape_string($conn, $_POST['houseNumber']);

    // Prepare and execute SQL statement to insert customer details into the customers table
    $sql = "INSERT INTO customers (first_name, last_name, email, city, country, mobile, address, user_id, house_number, date_created, time_created)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), CURTIME())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssis", $firstName, $lastName, $email, $city, $country, $mobile, $address, $userId, $houseNumber);
    $stmt->execute();

    // Check if the customer was successfully inserted
    if ($stmt->affected_rows > 0) {
        // Close prepared statement
        $stmt->close();
        
        // Redirect to customer list page
        header("Location: customer-list.php");
        exit();
    } else {
        echo "Error adding customer: " . $conn->error;
    }
} else {
    // Redirect to the error page or display an error message
    echo "Invalid request method";
}

// Close the database connection
$conn->close();
?>
