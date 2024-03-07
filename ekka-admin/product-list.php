<?php
require_once '../db.php';
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

	<!-- No Extra plugin used -->

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

		<?php include 'header.php';?>

			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
						<div>
							<h1>Product</h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Product</p>
						</div>
						<div>
							<a href="product-add.php" class="btn btn-primary"> Add Porduct</a>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card card-default">
								<div class="card-body">
									<div class="table-responsive">
										<table id="responsive-data-table" class="table"
											style="width:100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>Product</th>
													<th>Name</th>
													<th>Price</th>
													<th>Availability</th>
													<th>Category</th>
													<th>Date</th>
													<th> </th>
												</tr>
											</thead>

											<tbody>

												<?php
// Step 2: Query the database
$sql = "SELECT * FROM products INNER JOIN category ON category.category_id = products.category_id";
$result = mysqli_query($conn, $sql);

/// Step 3: Loop through the result set
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        // Determine whether to display "Yes" or "No" based on the "availability" column
        $availability = ($row["availability"] == 1) ? "Yes" : "No";

        // Display the retrieved data in the required HTML table format
        echo "<tr>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td><img src='../assets/images/product-image/" . $row['product_image'] . "' alt='Product Image' class='img-responsive' height='150px'> </td>";
        echo "<td>" . $row["product_name"] . " </td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . $availability . "<br><button type='button' class='btn btn-outline-warning' data-product-id='". $row["product_id"] ."'>Yes/No</button></td>"; 
        echo "<td>" . $row["category_name"] . "</td>";
        echo "<td>" . $row['date_added'] . "</td>";
        echo '<td>
				<div class="btn-group mb-1">
					<button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"	data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
						<span class="sr-only">Show</span>
					</button>

					<div class="dropdown-menu">
						<a class="dropdown-item" href="product-edit.php?product_id='. $row["product_id"] .'">Edit</a>
						<a class="dropdown-item delete-product" href="delete_product.php?product_id='.$row['product_id'].'">Delete</a>
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
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->


			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p>
						Copyright &copy; <span id="ec-year"></span><a class="text-primary"
						href="https://themeforest.net/user/ashishmaraviya" target="_blank"> Ekka Admin Dashboard</a>. All Rights Reserved.
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
	<script type="text/javascript">
$(document).ready(function() {
    $('.btn-outline-warning').click(function() {
        // Get the product ID from the data attribute
        var productId = $(this).data('product-id');
        
        // Send an AJAX request to update the product availability
        $.ajax({
            url: 'update_product_availability.php',
            type: 'POST',
            data: { product_id: productId },
            success: function(response) {
                // Reload the page or update the UI as needed
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
</script>

	<!-- Datatables -->
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