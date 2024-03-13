<?php
require_once '../db.php'; // Adjust the path as needed

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize commission details from the form
    $userId = intval($_POST['userId']); // Assuming 'userId' is the user ID
    $payment = floatval($_POST['payment']); // Assuming 'payment' is the payment amount
    $issuedBy = intval($_POST['id']); // Assuming 'id' is the ID of the issuer
    $referenceNumber = mysqli_real_escape_string($conn, $_POST['reference_number']); // Assuming 'reference_number' is the reference number
    $details = mysqli_real_escape_string($conn, $_POST['details']); // Assuming 'details' is the details of the commission

    // Prepare and execute SQL statement to insert commission details into the commissions table
    $sql = "INSERT INTO commissions (user_id, payment, issued_by, reference_number, details, date)
            VALUES (?, ?, ?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iddss", $userId, $payment, $issuedBy, $referenceNumber, $details);
    $stmt->execute();

    // Check if the commission was successfully inserted
    if ($stmt->affected_rows > 0) {
        // Close prepared statement
        $stmt->close();
        
        // Redirect to commission list page
        header("Location: commission.php");
        exit();
    } else {
        echo "Error adding commission: " . $conn->error;
    }
} else {
    // Redirect to the error page or display an error message
    echo "Invalid request method";
}

// Close the database connection
$conn->close();
?>
