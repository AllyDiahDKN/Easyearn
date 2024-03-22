<?php
// Include your database connection file here
require_once '../db.php';

// Check if admin ID is provided in the URL
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];

    // Retrieve admin data before deletion
    $sql = "SELECT * FROM deleted_admin WHERE id = '$adminId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch admin data
        $adminData = $result->fetch_assoc();

        // Insert admin data into admins table
        $insertQuery = "INSERT INTO admin (id, first_name, last_name, email, password, code, mobile, permission_type, activation, address_id, date_created, date_modified)
                        VALUES ('{$adminData['id']}', '{$adminData['first_name']}', '{$adminData['last_name']}', '{$adminData['email']}', '{$adminData['password']}', '{$adminData['code']}', '{$adminData['mobile']}', '{$adminData['permission_type']}', '{$adminData['activation']}', '{$adminData['address_id']}', '{$adminData['date_created']}', '{$adminData['date_modified']}')";

        if ($conn->query($insertQuery) === TRUE) {
            // Delete the admin from the deleted_admins table
            $deleteQuery = "DELETE FROM deleted_admin WHERE id = '$adminId'";
            if ($conn->query($deleteQuery) === TRUE) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting admin: " . $conn->error;
            }
        } else {
            echo "Error inserting into admins table: " . $conn->error;
        }
    } else {
        echo "Admin not found.";
    }
} else {
    echo "Admin ID not provided.";
}
?>
