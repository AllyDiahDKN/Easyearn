<?php
require_once '../db.php'; // Include your database connection file here
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">

    <!-- ekka CSS -->
    <link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

    <!-- FAVICON -->
    <link href="assets/img/favicon.png" rel="shortcut icon" />
</head>
<body>
    <div class="wrapper">
        <div class="ec-content-wrapper">
            <div class="content">
                <!-- Add Brand Modal -->
                <div class="modal-dialog modal-dialog-centered modal-lg" id="addUser" role="document">
                    <div class="modal-content">
                        <?php
                       if (isset($_GET['commission_id'])) {
                        $commissionId = $_GET['commission_id'];
                    
                        // Retrieve commission data for editing
                        $sql = "SELECT * FROM commission WHERE commission_id = '$commissionId'";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            // Fetch commission data
                            $commissionData = $result->fetch_assoc();
                    
                            // Check if the form is submitted for updating commission
                            if(isset($_POST['update_commission'])) {
                                $newUserId = $_POST['user_id'];
                                $newPayment = $_POST['payment'];
                                $newIssuedBy = $_POST['issued_by'];
                                $newReferenceNumber = $_POST['reference_number'];
                                $newDetails = $_POST['details'];
                    
                                // Update commission data in the commission table
                                $updateQuery = "UPDATE commission SET user_id = '$newUserId', payment = '$newPayment', issued_by = '$newIssuedBy', reference_number = '$newReferenceNumber', details = '$newDetails' WHERE commission_id = '$commissionId'";
                                if ($conn->query($updateQuery) === TRUE) {
                                    // Redirect back to commission.php or wherever you want
                                    header("Location: commission.php");
                                    exit();
                                } else {
                                    echo "Error updating commission: " . $conn->error;
                                }
                            }
                                ?>
                            <form method="post" action="">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Commission</h5>
    </div>

    <div class="modal-body px-4">
        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="chooseSeller">Paid To</label>
									<select name="user_id" id="userId" class="form-control" required>
						<option selected disabled>Select User *</option>
						<?php
						// Fetch user information from the database
						$sql = "SELECT user_id, first_name, last_name FROM user";
						$result = $conn->query($sql);

						// Check if there are users
						if ($result->num_rows > 0) {
							// Output the options
							while ($row = $result->fetch_assoc()) {
								$userId = $row['user_id'];
								$firstName = $row['first_name'];
								$lastName = $row['last_name'];
								// Check if this user is the selected user
								$selected = ($userId == $commissionData['user_id']) ? "selected" : "";
								echo "<option value=\"$userId\" $selected>$firstName $lastName ( $userId) </option>";
							}
						} else {
							echo "<option value=\"\">No users found</option>";
						}
						?>
    			</select>
                </div>
            </div>

			<div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="amount">Amount Paid</label>
                    <input type="number" class="form-control" id="amount" name="payment" placeholder="Enter payment amount" value="<?php echo $commissionData['payment']; ?>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="paidThrough">Issued by </label>
					<select name="issued_by" id="issued_by" class="form-control" required>
						<option selected disabled>Select User(Issuer) *</option>
						<?php
						// Fetch admin information from the database
						$sql = "SELECT email FROM admin";
						$result = $conn->query($sql);

						// Check if there are admins
						if ($result->num_rows > 0) {
							// Output the options
							while ($row = $result->fetch_assoc()) {
								$email = $row['email'];								
								// Check if this email is the selected issuer
								$selected = ($email == $commissionData['issued_by']) ? "selected" : "";
								echo "<option value=\"$email\" $selected>$email </option>";
							}
						} else {
							echo "<option value=\"\">No issuer found</option>";
						}
						?>
    			</select>
                    
                </div>
            </div>

			<div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="reference">Reference</label>
                    <input type="text" class="form-control" id="reference" name="reference_number" placeholder="Enter reference number" value="<?php echo $commissionData['reference_number']; ?>">
                </div>
            </div>
			<div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="reference">Details</label>
                    <select name="details" id="details" class="form-control" required>
						<option selected disabled>Select Mode *</option>
						<?php
						// Fetch detail_payment information from the database
						$sql = "SELECT id, mode_payment FROM detail_payment";
						$result = $conn->query($sql);

						// Check if there are detail_payment
						if ($result->num_rows > 0) {
							// Output the options
							while ($row = $result->fetch_assoc()) {
								$id = $row['id'];
								$mode_payment= $row['mode_payment'];
								// Check if this mode_payment is the selected detail
								$selected = ($mode_payment == $commissionData['details']) ? "selected" : "";
								echo "<option value=\"$mode_payment\" $selected>$mode_payment ( $id) </option>";
							}
						} else {
							echo "<option value=\"\">No mode found</option>";
						}
						?>
    			</select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer px-4">
        <a href="commission.php" type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-primary btn-pill" name="update_commission">Update Commission</button>
    </div>
</form>

                                <?php
                             } else {
                                echo "Commission not found.";
                            }
                        } else {
                            echo "Commission ID not provided.";
                        }
                        ?>
                    </div>
                </div>
            </div> <!-- End Content -->
        </div> <!-- End Content Wrapper -->
    </div> <!-- End Page Wrapper -->
</body>
</html>
<?php
mysqli_close($conn);
?>
