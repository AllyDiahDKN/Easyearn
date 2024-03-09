<?php
// Include your database connection file here
require_once '../db.php';

// Check if admin ID is provided in the URL
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Prepare and bind parameters
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $adminId);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch admin data
        $adminData = $result->fetch_assoc();

        // Insert admin data into deleted_admin table
        $stmt = $conn->prepare("INSERT INTO deleted_admin (id, username, email, password, first_name, last_name, code, mobile, permission_type, activation, address_id, date_created, date_modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssssiss", $adminData['id'], $adminData['username'], $adminData['email'], $adminData['password'], $adminData['first_name'], $adminData['last_name'], $adminData['code'], $adminData['mobile'], $adminData['permission_type'], $adminData['activation'], $adminData['address_id'], $adminData['date_created'], $adminData['date_modified']);

        if ($stmt->execute()) {
            // Delete the admin from the admin table
            $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
            $stmt->bind_param("i", $adminId);
            if ($stmt->execute()) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting admin: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_admin table: " . $conn->error;
        }
    } else {
        echo "Admin not found.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Admin ID not provided.";
}

// Close connection
$conn->close();
?>
