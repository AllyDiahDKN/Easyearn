<?php
// Include your database connection file here
require_once '../db.php';


// Check if commission ID is provided in the URL
if (isset($_GET['commission_id'])) {
    $commissionId = $_GET['commission_id'];

    // Prepare and bind parameters to select commission data
    $stmt = $conn->prepare("SELECT * FROM commission WHERE commission_id = ?");
    $stmt->bind_param("i", $commissionId);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Fetch commission data
        $commissionData = $result->fetch_assoc();

        // Insert commission data into deleted_commission table
        $stmt = $conn->prepare("INSERT INTO deleted_commission (commission_id, user_id, payment, issued_by, reference_number, details, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $commissionData['commission_id'], $commissionData['user_id'], $commissionData['payment'], $commissionData['issued_by'], $commissionData['reference_number'], $commissionData['details'], $commissionData['date']);

        if ($stmt->execute()) {
            // Delete the commission from the commission table
            $stmt = $conn->prepare("DELETE FROM commission WHERE commission_id = ?");
            $stmt->bind_param("i", $commissionId);
            if ($stmt->execute()) {
                // If successful, redirect back to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit();
            } else {
                echo "Error deleting commission: " . $conn->error;
            }
        } else {
            echo "Error inserting into deleted_commission table: " . $conn->error;
        }
    } else {
        echo "Commission not found.";
    }

    // Close statement
    $stmt->close();
} else {

    echo "Commission ID not provided.";
}

// Close connection
$conn->close();
?>
