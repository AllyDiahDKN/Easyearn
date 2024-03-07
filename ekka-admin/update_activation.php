<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Initialize response array
$response = array();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required parameter is set
    if (isset($_POST['user_id'])) {
        // Retrieve user ID from POST data
        $userId = $_POST['user_id'];

        // Retrieve current activation status from the database
        $sql = "SELECT activation, username, mobile, first_name FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $currentStatus = $row['activation'];
        $email = $row['username'];
        $mobile = $row['mobile'];
        $first_name = $row['first_name'];

        // Toggle activation status
        $newStatus = ($currentStatus == 'Active') ? 'Inactive' : 'Active';

        // Update activation status in the database
        $sql = "UPDATE user SET activation = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $userId);
        $success = $stmt->execute();

        // Check if the update operation was successful
        if ($success) {
            if ($newStatus == 'Active') {
                // Send activation email to the user
                $to = $email;
                $subject = 'Your EasyEarn Account has been Activated';
                $message = 'Dear '.$first_name.', Your account has been activated. You can now proceed to login and start earning for every sale you make.';
                $headers = 'From: registration@easyearn.co.tz' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
                $emailSent = mail($to, $subject, $message, $headers);

                // Send SMS to seller
                $api_key = 'ddbd10baf2166337';
                $secret_key = 'OWI4NTVmOWJiYWM3NGJlMmYwNjQwNTQ3NzY3NjMzYTUzMTQxNjEwZGViNmI1NDkwYjkxZjgwNzM2NDBmMGUyOQ==';
                $smsData = array(
                    'source_addr' => 'FairNWhite',
                    'encoding' => 0,
                    'schedule_time' => '',
                    'message' => 'Karibu EasyEarnTz! Your Account is now activated. Start earning Today!. For help, click this link to chat with us: https://wa.me/255677174020',
                    'recipients' => array(array('recipient_id' => '1', 'dest_addr' => $mobile))
                );
                $smsSent = sendSMS($api_key, $secret_key, $smsData);

                if ($emailSent && $smsSent) {
                    // Set success response
                    $response['success'] = true;
                    $response['message'] = "Activation status updated successfully to $newStatus";
                } else {
                    // Set error response if email or SMS failed to send
                    $response['success'] = false;
                    $response['message'] = "Failed to send activation notification";
                }
            } else {
                // Set success response for deactivation
                $response['success'] = true;
                $response['message'] = "Activation status updated successfully to $newStatus";
            }
        } else {
            // Set error response
            $response['success'] = false;
            $response['message'] = "Failed to update activation status";
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Missing parameter: Set error response
        $response['success'] = false;
        $response['message'] = "Missing user ID parameter";
    }
} else {
    // Invalid request method: Set error response
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Set response content type to JSON
header('Content-Type: application/json');

// Return JSON response
echo json_encode($response);

// Function to send SMS
function sendSMS($api_key, $secret_key, $smsData) {
    $Url = 'https://apisms.beem.africa/v1/send';
    $ch = curl_init($Url);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($smsData)
    ));
    $response = curl_exec($ch);
    if ($response === FALSE) {
        echo $response;
        die(curl_error($ch));
    }
    var_dump($response);
    return true; // Assuming SMS sent successfully
}
?>
