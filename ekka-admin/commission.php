<?php
require_once '../db.php';?>
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Ekka - Admin Dashboard HTML Template.">

	<title>Ekka - Admin Dashboard HTML Template.</title>

	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">

	<!-- PLUGINS CSS STYLE -->
	<link href="assets/plugins/simplebar/simplebar.css" rel="stylesheet" />

	<!-- Data Tables -->
	<link href='assets/plugins/data-tables/datatables.bootstrap5.min.css' rel='stylesheet'>
	<link href='assets/plugins/data-tables/responsive.datatables.min.css' rel='stylesheet'>

	<!-- ekka CSS -->
	<link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

	<!-- FAVICON -->
	<link href="assets/img/favicon.png" rel="shortcut icon" />
</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">

	<!-- WRAPPER -->
	<div class="wrapper">

			<!-- Header -->
			<?php include 'header.php';?>

			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Paid Commissions</h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Commission
							</p>
						</div>
						<div>
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#addUser"> Bulk Add
							</button>
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#addUser"> Add Commission
							</button>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="ec-vendor-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
										<table id="responsive-data-table" class="table">
											<thead>
												<tr>
                         <th>Id</th>
                          <th>Paid to</th>
                          <th>Amount Paid</th>
                          <th>Issued by</th>
                          <th>Reference</th>
                          <th>Details</th>
                          <th>Date Created</th>
                          <th> </th>
                        </tr>
											</thead>

											<tbody>
											
<?php
// Step 2: Query the database
$sql = "SELECT * FROM commission";
$result = mysqli_query($conn, $sql);

// Step 3: Loop through the result set
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
        $sql_orders = "SELECT first_name,last_name FROM user WHERE user_id = '".$row['user_id']."'";
        $result_orders = $conn->query($sql_orders);
        
        // Check if the query was successful and if it returned any rows
        if ($result_orders && $result_orders->num_rows > 0) {
            $row_orders = $result_orders->fetch_assoc();

            // Display the retrieved data in the required HTML table format
            echo "<tr>";
            echo "<td>" . $row["commission_id"] . "</td>";
            echo "<td>" . $row_orders["first_name"] . " " . $row_orders["last_name"] ." (".$row["user_id"].") </td>";
            echo "<td>".number_format($row['payment'],0)."</td>";
            echo "<td>" . $row["issued_by"] . "</td>";
            echo "<td>" . $row["reference_number"] . "</td>";
            echo "<td>" . $row["details"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo '<td>
                    <div class="btn-group mb-1">
                        <button type="button"
                            class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" data-display="static">
                            <span class="sr-only">Info</span>
                        </button>
                        <div class="dropdown-menu">
						<a class="dropdown-item" href="edit_commission.php?commission_id='.$row['commission_id'].'" name="Edit" id="commissionEdit">Edit</a>
						<a class="dropdown-item" href="deleted_commission.php?commission_id='.$row['commission_id'].'" name="Delete" id="commissionDelete">Delete</a>
                        </div>
                    </div>
                </td>';
            echo "</tr>";
        } else {
            // Handle case where no user data is found
            echo "<tr>";
            echo "<td colspan='7'>No user data found</td>";
            echo "</tr>";
        }
    }
} else {
    echo "<tr><td colspan='7'>0 results</td></tr>";
}
?>

					
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Add User Modal  -->
					<div class="modal fade modal-add-contact" id="addUser" tabindex="-1" role="dialog"
						aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content">
							<form method="post" action="save_commission.php">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Add Commission</h5>
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
								echo "<option value=\"$userId\">$firstName $lastName ( $userId) </option>";
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
                    <input type="number" class="form-control" id="amount" name="payment" placeholder="Enter payment amount">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="paidThrough">Issued by </label>
					<select name="issued_by" id="issued_by" class="form-control" required>
						<option selected disabled>Select User(Issuer) *</option>
						<?php
						// Fetch user information from the database
						$sql = "SELECT email FROM admin";
						$result = $conn->query($sql);

						// Check if there are admins
						if ($result->num_rows > 0) {
							// Output the options
							while ($row = $result->fetch_assoc()) {
								//$id = $row['id'];
								$email = $row['email'];								
								echo "<option value=\"$email\">$email </option>";
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
                    <input type="text" class="form-control" id="reference" name="reference_number" placeholder="Enter reference number">
                </div>
            </div>
			<div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="reference">Details</label>
                    <select name="details" id="details" class="form-control" required>
						<option selected disabled>Select Mode *</option>
						<?php
						// Fetch user information from the database
						$sql = "SELECT id, mode_payment FROM detail_payment";
						$result = $conn->query($sql);

						// Check if there are users
						if ($result->num_rows > 0) {
							// Output the options
							while ($row = $result->fetch_assoc()) {
								$id = $row['id'];
								$mode_payment= $row['mode_payment'];
								
								echo "<option value=\"$mode_payment\">$mode_payment ( $id) </option>";
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
        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-pill">Save Commission</button>
    </div>
</form>

							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->

			<!-- Footer -->
			<?php include 'footer.php';?>

		</div> <!-- End Page Wrapper -->
	</div> <!-- End Wrapper -->

	<!-- Common Javascript -->
	<script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/plugins/simplebar/simplebar.min.js"></script>
	<script src="assets/plugins/jquery-zoom/jquery.zoom.min.js"></script>
	<script src="assets/plugins/slick/slick.min.js"></script>

	<!-- Data Tables -->
	<script src='assets/plugins/data-tables/jquery.datatables.min.js'></script>
	<script src='assets/plugins/data-tables/datatables.bootstrap5.min.js'></script>
	<script src='assets/plugins/data-tables/datatables.responsive.min.js'></script>

	<!-- Option Switcher -->
	<script src="assets/plugins/options-sidebar/optionswitcher.js"></script>

	<!-- Ekka Custom -->
	<script src="assets/js/ekka.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>