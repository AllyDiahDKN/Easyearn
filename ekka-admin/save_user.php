<?php
require_once '../db.php'; // Adjust the path as needed

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user details from the form
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    //$code = mysqli_real_escape_string($conn, $_POST['code']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    //$activation = intval($_POST['activation']);
    $addressId = intval($_POST['address_id']);
    //$commission = floatval($_POST['commission']);

    // Prepare and execute SQL statement to insert user details into the users table
    $sql = "INSERT INTO user (first_name, last_name, username, mobile, password, address_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $lastName, $username, $mobile, $password, $addressId);
    $stmt->execute();

    // Check if the user was successfully inserted
    if ($stmt->affected_rows > 0) {
        // Close prepared statement
        $stmt->close();
        
        // Redirect to user list page
        header("Location: user-list.php");
        exit();
    } else {
        echo "Error adding user: " . $conn->error;
    }
} else {
    // Redirect to the error page or display an error message
    echo "Invalid request method";
}

// Close the database connection
$conn->close();
?>
