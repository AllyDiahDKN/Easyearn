<?php
// Include your database connection file
require_once '../db.php'; // Adjust the path as needed
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

	<!-- ekka CSS -->
	<link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

	<!-- FAVICON -->
	<link href="assets/img/favicon.png" rel="shortcut icon" />

</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">

	<!-- WRAPPER -->
	<?php
	include 'header.php';
	?>

			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
						<div>
							<h1>Add Product</h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Product</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card card-default">
								<div class="card-body">
									<div class="row ec-vendor-uploads">
										<form class="row g-3" action="insert_product.php" method="post" enctype="multipart/form-data">
    <div class="ec-vendor-upload-detail">
    	  <!-- Add image upload field -->
    <div class="col-md-6">
        <label class="form-label">Product Image</label>
        <input type="file" class="form-control" name="product_image">
    </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Product name</label>
                <input type="text" class="form-control" name="product_name">
            </div>
            <div class="col-md-6">
                <label class="form-label">Select Category</label>
                <select name="category_id" class="form-select">
<?php
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['category_id']}'>{$row['category_name']}</option>";
    }
} else {
    echo "<option value='' disabled>No categories found</option>";
}
?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Select Brand</label>
                <select name="brand_id" class="form-select">
<?php
$sql = "SELECT brand_id, brand_name FROM brands";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['brand_id']}'>{$row['brand_name']}</option>";
    }
} else {
    echo "<option value='' disabled>No brands found</option>";
}
?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Availability</label>
                <select name="availability" class="form-select">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-8 mb-25">
                <label class="form-label">Size</label>
                <div class="form-checkbox-box">
<?php
$sql = "SELECT size_id, size_name FROM size";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='form-check form-check-inline'>
            <input type='checkbox' name='sizes[]' value='{$row['size_id']}'>
            <label>{$row['size_name']}</label>
        </div>";
    }
} else {
    echo "<p>No sizes found</p>";
}
?>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Price (In Tsh)</label>
                <input type="number" class="form-control" name="price">
            </div>
            <div class="col-md-6">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity">
            </div>
            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" rows="4" name="description"></textarea>
            </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Code</label>
                <input type="text" class="form-control" name="code">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Add Product</button>
            </div>
    </div>
        </form>

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
	<script src="assets/plugins/tags-input/bootstrap-tagsinput.js"></script>
	<script src="assets/plugins/simplebar/simplebar.min.js"></script>
	<script src="assets/plugins/jquery-zoom/jquery.zoom.min.js"></script>
	<script src="assets/plugins/slick/slick.min.js"></script>

	<!-- Option Switcher -->
	<script src="assets/plugins/options-sidebar/optionswitcher.js"></script>

	<!-- Ekka Custom -->
	<script src="assets/js/ekka.js"></script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>