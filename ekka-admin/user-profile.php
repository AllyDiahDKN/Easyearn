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

	<link href="../../../../../cdn.jsdelivr.net/npm/%40mdi/font%404.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

	<!-- PLUGINS CSS STYLE -->
	<link href="assets/plugins/simplebar/simplebar.css" rel="stylesheet" />


	<!-- Ekka CSS -->
	<link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

	<!-- FAVICON -->
	<link href="assets/img/favicon.png" rel="shortcut icon" />

</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">

	<!-- WRAPPER -->
	<div class="wrapper">

		<?php include 'header.php';?>

			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Seller Details</h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Profile
							</p>
						</div>
						<div>
							<a href="user-list.php" class="btn btn-primary">Edit</a>
						</div>
					</div>
					<div class="card bg-white profile-content">
						<div class="row">
							<?php
// Assume you have already established a database connection
require_once '../db.php';
// Get the user ID from the URL
$userID = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Check if the user ID is provided in the URL
if ($userID) {
    // Fetch user data from the database
    $sql = "SELECT * FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user data exists
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $firstName = $userData['first_name'];
        $lastName = $userData['last_name'];
        $email = $userData['username'];
        $activation = $userData['activation'];
        $mobile = $userData['mobile'];
        $dateJoined = $userData['date_created'];

        // Display user profile information
        ?>
        <div class="col-lg-4 col-xl-3">
            <div class="profile-content-left profile-left-spacing">
                <div class="text-center widget-profile px-0 border-0">

                    <div class="card-body">
                        <h4 class="py-2 text-dark"><?php echo $firstName . " " . $lastName; ?></h4>
                        <p>User ID: <?php echo $userID; ?></p>
                        <a class="btn btn-primary my-3" href="#"><?php echo $activation; ?></a>
                    </div>
                </div>

                <div class="d-flex justify-content-between ">
                	<?php
        // Query to get total sales for the user
        $salesQuery = "SELECT SUM(total) AS total_sales FROM orders WHERE user_id = $userID AND payment = 'paid'";

        // Execute the sales query
        $salesResult = $conn->query($salesQuery);

        if ($salesResult) {
            // Fetch the result as an associative array
            $salesRow = $salesResult->fetch_assoc();

            // Get the total sales
            $totalSales = $salesRow['total_sales'];
            ?>

                    <div class="text-center pb-4">
                        <h6 class="text-dark pb-2"><?php echo number_format($totalSales);?></h6>
						<p>Total Sales</p>
                    </div>

			<?php
        } else {
            // Handle the sales query error
            echo "<h3>Error executing sales query: " . $conn->error . "</h3>";
        }
        ?>
          
          <?php

        // Query to get total sales for the user
        $salesQuery = "SELECT SUM(total) AS total_sales FROM orders WHERE user_id = $userID AND payment = 'unpaid'";

        // Execute the sales query
        $salesResult = $conn->query($salesQuery);

        if ($salesResult) {
            // Fetch the result as an associative array
            $salesRow = $salesResult->fetch_assoc();

            // Get the total sales
            $totalSales = $salesRow['total_sales'];
            ?>

                    <div class="text-center pb-4">
                        <h6 class="text-dark pb-2"><?php echo number_format($totalSales);?></h6>
						<p>Total Unpaid</p>
                    </div>

			<?php
        } else {
            // Handle the sales query error
            echo "<h3>Error executing sales query: " . $conn->error . "</h3>";
        }

        ?>
    </div>

                <hr class="w-100">

                <div class="contact-info pt-4">
                    <h5 class="text-dark">Contact Information</h5>
                    <p class="text-dark font-weight-medium pt-24px mb-2">Email address</p>
                    <p><?php echo $email; ?></p>
                    <p class="text-dark font-weight-medium pt-24px mb-2">Phone Number</p>
                    <p><?php echo $mobile; ?></p>
                    <p class="text-dark font-weight-medium pt-24px mb-2">Date Joined</p>
                    <p><?php echo $dateJoined; ?></p>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "User not found";
    }
} else {
    echo "User ID not provided in the URL";
}
?>

							<div class="col-lg-8 col-xl-9">
								<div class="profile-content-right profile-right-spacing py-5">
									<ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myProfileTab" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
												data-bs-target="#profile" type="button" role="tab"
												aria-controls="profile" aria-selected="true">Profile</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="settings-tab" data-bs-toggle="tab"
												data-bs-target="#settings" type="button" role="tab"
												aria-controls="settings" aria-selected="false">Settings</button>
										</li>
									</ul>
									<div class="tab-content px-3 px-xl-5" id="myTabContent">

										<div class="tab-pane fade show active" id="profile" role="tabpanel"
											aria-labelledby="profile-tab">
											<div class="tab-widget mt-5">
												<div class="row">
 <?php
 $salesQuery = "SELECT SUM(total) AS total_sales FROM orders WHERE user_id = $userID AND payment = 'paid'";

        // Execute the sales query
        $salesResult = $conn->query($salesQuery);

        if ($salesResult) {
            // Fetch the result as an associative array
            $salesRow = $salesResult->fetch_assoc();

            // Get the total sales
            $totalSales = $salesRow['total_sales'];
        }
        // Query to get commission for the user
        $commissionQuery = "SELECT commission FROM user WHERE user_id = $userID";

        // Execute the commission query
        $commissionResult = $conn->query($commissionQuery);

        if ($commissionResult) {
            // Fetch the result as an associative array
            $commissionRow = $commissionResult->fetch_assoc();

            // Get the commission
            $commission = $commissionRow['commission'];

            // Calculate earnings
            $commissionPercentage = $commission / 100;
            $earnings = $totalSales * $commissionPercentage;
            ?>
            <div class="col-xl-4">
														<div class="media widget-media p-3 bg-white border">
															<div class="icon rounded-circle bg-warning mr-3">
																<i class="mdi mdi-cart-outline text-white "></i>
															</div>

															<div class="media-body align-self-center">
																<h4 class="text-primary mb-2"><?php echo number_format($earnings);?></h4>
																<p>Earned</p>
															</div>
														</div>
													</div>
<?php 
        } else {
            // Handle the commission query error
            echo "<h3>Error executing commission query: " . $conn->error . "</h3>";
        }
        ?>													

 <?php
        $paidQuery = "SELECT SUM(payment) AS paid FROM commission WHERE user_id = $userID";

        // Execute the paid query
        $paidResult = $conn->query($paidQuery);

        if ($paidResult) {
            // Fetch the result as an associative array
            $paidRow = $paidResult->fetch_assoc();

            // Get the total paid
            $paid = $paidRow['paid'];
            ?>

<div class="col-xl-4">
				<div class="media widget-media p-3 bg-white border">
					<div class="icon rounded-circle mr-3 bg-primary">
						<i class="mdi mdi-account-outline text-white "></i>
					</div>

					<div class="media-body align-self-center">
						<h4 class="text-primary mb-2"><?php echo number_format($paid ?? 0);?></h4>
						<p>Total Sales</p>
					</div>
				</div>
			</div>
			<?php
        } else {
            // Handle the paid query error
            echo "<h3>Error executing paid query: " . $conn->error . "</h3>";
        }
        ?>

													

													<div class="col-xl-4">
														<div class="media widget-media p-3 bg-white border">
															<div class="icon rounded-circle mr-3 bg-success">
																<i class="mdi mdi-ticket-percent text-white "></i>
															</div>

															<div class="media-body align-self-center">
																<?php
        // Calculate pending amount
        $pending = $earnings - ($paid ?? 0);
        ?>
        																<h4 class="text-primary mb-2"><?php echo number_format(($pending >= 0 ? $pending : 0));?></h4>

																<p>Voucher</p>
															</div>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-12">
														<!-- Recent Order Table -->
														<div class="card card-default card-table-border-none ec-tbl" id="recent-orders">
															<div class="card-header justify-content-between">
																<h2>Recent Orders</h2>
															</div>

															<div class="card-body pt-0 pb-0 table-responsive">
																<?php

// SQL query to retrieve orders of the user with customer name
$orderQuery = "SELECT o.order_number AS order_id, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, o.total, u.commission, o.approval
               FROM orders o
               INNER JOIN customers c ON o.customer_id = c.id
               INNER JOIN user u ON o.user_id = u.user_id
               WHERE o.user_id = $userID";

// Execute the query
$result = $conn->query($orderQuery);

// Check if the query was successful
if (!$result) {
    // Query execution failed, handle the error
    echo "Error executing query: " . $conn->error;
} else {
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output table header
        echo '<table class="table">
                <thead>
                    <tr>
	                    <th>Order ID</th>
						<th>Customer</th>
						<th>Total</th>
						<th>Commission</th>
						<th>Status</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        // Output table rows
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['order_id'];
            $customerName = $row['customer_name'];
            $total = $row['total'];
            $commissionPercentage = $row['commission'];
            $status = $row['approval'];

            // Calculate commission
            $commission = $total * ($commissionPercentage / 100);

            echo '<tr>
				<td>' . $orderId . '</td>
				<td>' . $customerName . '</td>
                    <td>' . $total . '</td>
                    <td>' . $commission . '</td>
                    <td>' . $status . '</td>
				
				<td class="text-right">
					<div
						class="dropdown show d-inline-block widget-dropdown">
						<a class="dropdown-toggle icon-burger-mini"
							href="#" role="button"
							id="dropdown-recent-order1"
							data-bs-toggle="dropdown"
							aria-haspopup="true"
							aria-expanded="false"
							data-display="static"></a>

						<ul class="dropdown-menu dropdown-menu-right"
							aria-labelledby="dropdown-recent-order1">
							<li class="dropdown-item">
								<a href="../order_invoice.php?order_number=' . $orderId . '">View</a>
							</li>

							<li class="dropdown-item">
								<a href="#">Remove</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>';
        }

        // Close table body and table
        echo '</tbody></table>';
    } else {
        // No orders found for the user
        echo '<p>No orders found.</p>';
    }
}
?>
															</div>
														</div>
													</div>
													
												</div>
											</div>
										</div>

										<div class="tab-pane fade" id="settings" role="tabpanel"
											aria-labelledby="settings-tab">
											<div class="tab-pane-content mt-5">
												<form>
													<div class="form-group row mb-6">
														<label for="coverImage"
															class="col-sm-4 col-lg-2 col-form-label">User Image</label>
														<div class="col-sm-8 col-lg-10">
															<div class="custom-file mb-1">
																<input type="file" class="custom-file-input"
																	id="coverImage" required>
																<label class="custom-file-label" for="coverImage">Choose
																	file...</label>
																<div class="invalid-feedback">Example invalid custom
																	file feedback</div>
															</div>
														</div>
													</div>

													<div class="row mb-2">
														<div class="col-lg-6">
															<div class="form-group">
																<label for="firstName">First name</label>
																<input type="text" class="form-control" id="firstName"
																	value="First name">
															</div>
														</div>

														<div class="col-lg-6">
															<div class="form-group">
																<label for="lastName">Last name</label>
																<input type="text" class="form-control" id="lastName"
																	value="Last name">
															</div>
														</div>
													</div>

													<div class="form-group mb-4">
														<label for="userName">User name</label>
														<input type="text" class="form-control" id="userName"
															value="User name">
														<span class="d-block mt-1">Accusamus nobis at omnis consequuntur
															culpa tempore saepe animi.</span>
													</div>

													<div class="form-group mb-4">
														<label for="email">Email</label>
														<input type="email" class="form-control" id="email"
															value="ekka.example@gmail.com">
													</div>

													<div class="form-group mb-4">
														<label for="oldPassword">Old password</label>
														<input type="password" class="form-control" id="oldPassword">
													</div>

													<div class="form-group mb-4">
														<label for="newPassword">New password</label>
														<input type="password" class="form-control" id="newPassword">
													</div>

													<div class="form-group mb-4">
														<label for="conPassword">Confirm password</label>
														<input type="password" class="form-control" id="conPassword">
													</div>

													<div class="d-flex justify-content-end mt-5">
														<button type="submit"
															class="btn btn-primary mb-2 btn-pill">Update
															Profile</button>
													</div>
												</form>
											</div>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->

			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p>
						Copyright &copy; <span id="ec-year"></span><a class="text-primary"
							href="https://themeforest.net/user/ashishmaraviya" target="_blank"> Ekka Admin
							Dashboard</a>. All Rights Reserved.
					</p>
				</div>
			</footer>

		</div> <!-- End Page Wrapper -->
	</div> <!-- End Wrapper -->


	<!-- Common Javascript -->
	<script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/plugins/simplebar/simplebar.min.js"></script>
	<script src="assets/plugins/jquery-zoom/jquery.zoom.min.js"></script>
	<script src="assets/plugins/slick/slick.min.js"></script>

	<!-- Option Switcher -->
	<script src="assets/plugins/options-sidebar/optionswitcher.js"></script>

	<!-- Ekka Custom -->
	<script src="assets/js/ekka.js"></script>

</body>
</html>