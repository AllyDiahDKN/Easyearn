<?php
// Include your database connection file here
require_once '../db.php';

// Check if user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Retrieve user data before deletion
    $sql = "SELECT * FROM deleted_user WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $userData = $result->fetch_assoc();

        // Insert user data into user table
        $insertQuery = "INSERT INTO user (user_id, first_name, last_name, username, code, mobile, Transfer_to, password, activation, address_id, commission, date_modified, date_created)
                        VALUES ('{$userData['user_id']}', '{$userData['first_name']}', '{$userData['last_name']}', '{$userData['username']}', '{$userData['code']}', '{$userData['mobile']}', '{$userData['Transfer_to']}', '{$userData['password']}', '{$userData['activation']}', '{$userData['address_id']}', '{$userData['commission']}', '{$userData['date_modified']}', '{$userData['date_created']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the user from the deleted_user table
            $deleteQuery = "DELETE FROM deleted_user WHERE user_id = '$user_id'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting user: " . $conn->error;
            }
        } else {
            echo "Error inserting into user table: " . $conn->error;
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "User ID not provided.";
}
?>
