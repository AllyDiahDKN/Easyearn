<?php
// Include your database connection file here
require_once '../db.php';

// Check if user ID is provided in the URL
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Retrieve user data before deletion
    $sql = "SELECT * FROM user WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $userData = $result->fetch_assoc();

        // Insert user data into deleted_user table
        $insertQuery = "INSERT INTO deleted_user (user_id, first_name, last_name, username, code, mobile, password, activation, address_id, commission, date_modified, date_created)
                        VALUES (
                            '{$userData['user_id']}', 
                            '{$userData['first_name']}', 
                            '{$userData['last_name']}', 
                            '{$userData['username']}', 
                            '{$userData['code']}', 
                            '{$userData['mobile']}', 
                            '{$userData['password']}', 
                            '{$userData['activation']}', 
                            '{$userData['address_id']}', 
                            '{$userData['commission']}', 
                            '{$userData['date_modified']}', 
                            '{$userData['date_created']}'
                        )";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the user from the user table
            $deleteQuery = "DELETE FROM user WHERE user_id = '$userId'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting user: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_user table: " . $conn->error;
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "User ID not provided.";
}
?>
