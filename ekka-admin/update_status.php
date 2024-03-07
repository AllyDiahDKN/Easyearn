<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required parameters are set
    if (isset($_POST['status'], $_POST['order_id'], $_POST['type'])) {
        // Include your database connection file
        require_once '../db.php'; // Adjust the path as needed

        // Sanitize and validate input data
        $status = $_POST['status'];
        $orderId = $_POST['order_id'];
        $type = $_POST['type']; // Type of status to update (approval or payment)

        // Update the status in the database based on the provided type
        $columnName = ($type === 'approval') ? 'approval' : 'payment'; // Column name based on type

        // Prepare the SQL statement
        $sql = "UPDATE orders SET $columnName = ? WHERE order_number = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);

        // Execute the statement
        if ($stmt->execute()) {
            // Success: Return a success message or status code
            echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
            // Send email and SMS notifications for Approved status
            if ($type === 'approval' && $status === 'Approved') {
                sendApprovalNotifications($conn, $orderId);
            }
        } else {
            // Error: Return an error message or status code
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Missing parameters: Return an error message or status code
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    }
} else {
    // Invalid request method: Return an error message or status code
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Function to send email and SMS notifications for Approved status
function sendApprovalNotifications($conn, $orderId) {
    // Query to retrieve order details
    $sql = "SELECT o.*, u.username AS email_user, u.mobile AS mobile_user,
                c.first_name, c.last_name, c.email, c.mobile
            FROM orders o
            INNER JOIN user u ON o.user_id = u.user_id
            INNER JOIN customers c ON o.customer_id = c.id
            WHERE o.order_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If order details are found, send notifications
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userEmail = $row['email_user'];
        $userMobile = $row['mobile_user'];
        $customerEmail = $row['email'];
        $customerMobile = $row['mobile'];
        $firstName = $row['first_name'];
        $total = $row['Total'];

        // Send email notification to admin
        $to = 'order@easyearn.co.tz';
        $subject = "Order $orderId is Approved";
        $message = "Hi, Order Number $orderId is approved and ready for payment and delivery.";
        $headers = "From: sales@footsteptz.com\r\n";
        $headers .= "CC: admin@easyeasrn.co.tz\r\n";
        mail($to, $subject, $message, $headers);

        // Send email notification to seller
        $to = $userEmail;
        $subject = "Your Order #$orderId is Approved";
        $message = "Hi, Your Order Number $orderId of Tsh.$total is approved and is ready for your customer to make payment. Payment details have been sent to the customer by email and SMS. Kindly follow up with your customer to make payment.";
        $headers = "From: order@easyearn.co.tz\r\n";
        $headers .= "CC: order@easyearn.co.tz\r\n";
        $headers .= "BCC: admin@easyearn.co.tz\r\n";
        mail($to, $subject, $message, $headers);

        // Send SMS notification to seller
        $api_key = 'ddbd10baf2166337';
        $secret_key = 'OWI4NTVmOWJiYWM3NGJlMmYwNjQwNTQ3NzY3NjMzYTUzMTQxNjEwZGViNmI1NDkwYjkxZjgwNzM2NDBmMGUyOQ==';
        $postData = array(
            'source_addr' => 'FairNWhite',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => "Order #$orderId of $total is Approved. Follow up with your customer to make payment",
            'recipients' => [array('recipient_id' => '1', 'dest_addr' => $userMobile)]
        );
        $url = 'https://apisms.beem.africa/v1/send';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);

        // Send SMS notification to customer
        $postData['message'] = "Hi, Your order of Tsh$total (excl delivery charges) is received from $firstName. Your Order number is $orderId, please proceed to make payment through Voda Lipa 5269589 (MALAK M TRADING). Karibu!";
        $postData['recipients'][0]['dest_addr'] = $customerMobile;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
?>