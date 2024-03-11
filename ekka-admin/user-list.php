<?php
require_once '../db.php';

if(isset($_POST['export_excel'])) {
    // Fetch data from the database
    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);

    // Create Excel file
    $filename = "seller_list_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Start Excel file content
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Total Sales</th><th>Commission Earned</th><th>Commission Unpaid</th><th>Commission Paid</th><th>Activation</th><th>Joined On</th></tr></thead>";
    echo "<tbody>";
    
    // Loop through the result set and add rows to Excel file
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sql_orders = "SELECT SUM(Total) AS grand_total FROM orders WHERE Payment='paid' AND user_id = '".$row['user_id']."'";
        $result_orders = $conn->query($sql_orders);
        $row_orders = $result_orders->fetch_assoc();

        $sql_paid = "SELECT SUM(payment) AS paid_commission FROM commission WHERE user_id = '".$row['user_id']."'";
        $result_paid = $conn->query($sql_paid);
        $row_paid = $result_paid->fetch_assoc();         


        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["first_name"] . " " . $row["last_name"] ." </td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["mobile"] . "</td>";
        echo "<td>".number_format($row_orders['grand_total'], 0)."</td>";
        
        if ($row_orders['grand_total'] >= 3000000) {
            $under_3m = 3000000 * 0.07;
            $extra_of_3m = $row_orders['grand_total'] - 3000000;
            $above_3m = $extra_of_3m * 0.1;
            $commission_earned = $under_3m + $above_3m;
            echo "<td>".number_format($commission_earned, 0)."</td>";
        } else {
            $commission_earned = $row_orders['grand_total'] * 0.07;
            echo "<td>".number_format($commission_earned, 0)."</td>";
        }
        
        echo "<td>".number_format(($commission_earned - $row_paid['paid_commission']), 0)."</td>";
        echo "<td>".number_format($row_paid['paid_commission'], 0)."</td>";
        echo "<td>".$row['activation']."<br><button type='button' class='btn btn-outline-warning toggle-activation' data-user-id='". $row["user_id"] ."'>Yes/No</button>
</td>";
        echo "<td>" . $row["date_created"] . "</td>";
    }
} else {
    echo "0 results";
}           
    echo "</tbody>";
    echo "</table>";
    exit(); // Exit after generating Excel file
}
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
							<h1>Seller List</h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Seller
							</p>
						</div>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <button type="submit" name="export_excel" class="btn btn-primary">Excel</button>
                </form>	
						<div>
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#addUser"> Add Seller
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
													<th>ID</th>
													<th>Name</th>
													<th>Email</th>
													<th>Mobile</th>
													<th>Total Sales</th>
													<th>Commission Earned</th>
													<th>Commission Unpaid</th>
													<th>Commission Paid</th>
													<th>Activation</th>
													<th>Joined On</th>
													<th> </th>
												</tr>
											</thead>

											<tbody>
						<?php
// Step 2: Query the database
$sql = "SELECT * FROM user ORDER BY user_id DESC"; 
$result = $conn->query($sql);

// Step 3: Loop through the result set
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sql_orders = "SELECT SUM(Total) AS grand_total FROM orders WHERE Payment='paid' AND user_id = '".$row['user_id']."'";
        $result_orders = $conn->query($sql_orders);
        $row_orders = $result_orders->fetch_assoc();

        $sql_paid = "SELECT SUM(payment) AS paid_commission FROM commission WHERE user_id = '".$row['user_id']."'";
        $result_paid = $conn->query($sql_paid);
        $row_paid = $result_paid->fetch_assoc();

        // Step 4: Display the retrieved data in the required HTML table format
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["first_name"] . " " . $row["last_name"] ." </td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["mobile"] . "</td>";
        echo "<td>".number_format($row_orders['grand_total'], 0)."</td>";
        
        if ($row_orders['grand_total'] >= 3000000) {
            $under_3m = 3000000 * 0.07;
            $extra_of_3m = $row_orders['grand_total'] - 3000000;
            $above_3m = $extra_of_3m * 0.1;
            $commission_earned = $under_3m + $above_3m;
            echo "<td>".number_format($commission_earned, 0)."</td>";
        } else {
            $commission_earned = $row_orders['grand_total'] * 0.07;
            echo "<td>".number_format($commission_earned, 0)."</td>";
        }
        
        echo "<td>".number_format(($commission_earned - $row_paid['paid_commission']), 0)."</td>";
        echo "<td>".number_format($row_paid['paid_commission'], 0)."</td>";
        echo "<td>".$row['activation']."<br><button type='button' class='btn btn-outline-warning toggle-activation' data-user-id='". $row["user_id"] ."'>Yes/No</button>
</td>";
        echo "<td>" . $row["date_created"] . "</td>";
        echo '<td>
                    <div class="btn-group mb-1">
                        
                        <button type="button"
                            class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" data-display="static">
                            <span class="sr-only">Info</span>
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="user-profile.php?user_id='.$row["user_id"].'">View</a>
                            <a class="dropdown-item" href="edit_user.php?user_id='.$row['user_id'].'" name="Edit" id="userEdit">Edit</a>
							<a class="dropdown-item" href="deleted_user.php?user_id='.$row['user_id'].'" name="Delete" id="userDelete">Delete</a>
                        </div>
                    </div>
                </td>';
        echo "</tr>";
    }
} else {
    echo "0 results";
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
							<form action="save_user.php" method="post">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Seller</h5>
    </div>

    <div class="modal-body px-4">
        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">First name</label>
                    <input type="text" class="form-control" name="first_name" id="firstName" value="John">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="lastName">Last name</label>
                    <input type="text" class="form-control" name="last_name" id="lastName" value="Deo">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="userName">User name</label>
                    <input type="email" class="form-control" name="username" id="userName" value="johnexample@gmail.com">
                </div>
            </div>            

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" value="">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

          <!--  <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="activation">Activation</label>
                    <select class="form-select" name="activation" id="activation">
                        <option value="0">Inactive</option>
                        <option value="1">Active</option>
                    </select>
                </div>
            </div>-->

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="address_id">Address ID</label>
                    <input type="text" class="form-control" name="address_id" id="address_id" value="">
                </div>
            </div>

          <!--   <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="commission">Commission</label>
                    <input type="number" class="form-control" name="commission" id="commission" value="">
                </div>
            </div>-->

        </div>
    </div>

    <div class="modal-footer px-4">
        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-pill">Save Seller</button>
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
	<script>
$(document).ready(function() {
    // Event handler for button click
    $('.toggle-activation').click(function() {
        // Get the user ID from the data attribute
        var userId = $(this).data('user-id');

        // Send AJAX request to update activation status
        $.ajax({
            url: 'update_activation.php',
            type: 'POST',
            data: { user_id: userId },
            success: function(response) {
                // Reload the page or update UI as needed
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
</script>


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