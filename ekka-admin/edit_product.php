<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
require_once '../db.php'; // Adjust the path as needed

// Retrieve product ID from URL parameter
if (isset($_GET['product_id'])) {
    $productId = mysqli_real_escape_string($conn, $_GET['product_id']);
} else {
    echo "Product ID not provided.";
    exit;
}

// Initialize product data array
$productData = array();

// Check if product_id is set in the URL parameters
if (isset($_GET['product_id'])) {
    // Sanitize the product_id to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_GET['product_id']);

    // Prepare and execute SQL query to fetch product data
    $sql = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch product data
        $productData = $result->fetch_assoc();
    } else {
        // Product not found
        echo "Product not found.";
        exit;
    }
} else {
    // Product ID parameter not provided
    echo "Product ID not provided.";
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = mysqli_real_escape_string($conn, $_POST['product_name']);
    $categoryID = mysqli_real_escape_string($conn, $_POST['category_id']);
    $brandID = mysqli_real_escape_string($conn, $_POST['brand_id']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : array(); // Assuming this is an array of selected size IDs

    // Initialize SQL UPDATE query
    $updateQuery = "UPDATE products SET";

    // Initialize array to track updated fields
    $updatedFields = array();

    // Check if product name has been modified
    if ($productName !== $productData['product_name']) {
        $updateQuery .= " product_name = '$productName',";
        $updatedFields[] = 'product_name';
    }

// Check if category_id has been modified
if ($categoryID !== $productData['category_id']) {
    $updateQuery .= " category_id = '$categoryID',";
    $updatedFields[] = 'category_id';
}

// Check if brand_id has been modified
if ($brandID !== $productData['brand_id']) {
    $updateQuery .= " brand_id = '$brandID',";
    $updatedFields[] = 'brand_id';
}

// Check if availability has been modified
if ($availability !== $productData['availability']) {
    $updateQuery .= " availability = '$availability',";
    $updatedFields[] = 'availability';
}

// Check if price has been modified
if ($price !== $productData['price']) {
    $updateQuery .= " price = '$price',";
    $updatedFields[] = 'price';
}

// Check if quantity has been modified
if ($quantity !== $productData['stock']) {
    $updateQuery .= " stock = '$quantity',"; // Assuming stock represents the quantity
    $updatedFields[] = 'quantity';
}

// Check if description has been modified
if ($description !== $productData['description']) {
    $updateQuery .= " description = '$description',";
    $updatedFields[] = 'description';
}

// Check if code has been modified
if ($code !== $productData['code']) {
    $updateQuery .= " code = '$code',";
    $updatedFields[] = 'code';
}

    // Check if any size has been added or removed
    $currentSizes = array_map('intval', explode(',', $productData['sizes'])); // Convert size IDs to integers
    sort($currentSizes);
    sort($sizes);
    if ($sizes !== $currentSizes) {
    // Delete existing size entries for the product
    $deleteQuery = "DELETE FROM product_size WHERE product_id = '$productId'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Insert new size entries for the product
        foreach ($sizes as $sizeId) {
            $insertQuery = "INSERT INTO product_size (product_id, size_id) VALUES ('$productId', '$sizeId')";
            if (!$conn->query($insertQuery)) {
                echo "Error inserting size: " . $conn->error;
            }
        }
    } else {
        echo "Error deleting sizes: " . $conn->error;
    }
}

    // Remove the trailing comma from the update query
    $updateQuery = rtrim($updateQuery, ',');

    // Add WHERE clause to specify the product to update
    $updateQuery .= " WHERE product_id = '$productId'";

    // Execute the update query
    if ($conn->query($updateQuery) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . $conn->error;
    }
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
                <h1>Edit Product</h1>
                <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>Product</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="row ec-vendor-uploads">
                            <?php
// Initialize product data array
$productData = array();

// Check if product_id is set in the URL parameters
if (isset($_GET['product_id'])) {
    // Sanitize the product_id to prevent SQL injection
    $productId = mysqli_real_escape_string($conn, $_GET['product_id']);

    // Prepare and execute SQL query to fetch product data
    $sql = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = $conn->query($sql);

    // Check if the query execution was successful
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch product data
            $productData = $result->fetch_assoc();
        } else {
            // Product not found
            throw new Exception("Product not found.");
        }
    } else {
        // If there's an error in the SQL query, throw an exception
        throw new Exception("Error: " . $conn->error);
    }
} else {
    // Product ID parameter not provided
    throw new Exception("Product ID not provided.");
}

// Fetch categories
$categories = array();
$sql = "SELECT category_id, category_name FROM category";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = $row['category_name'];
    }
}

// Fetch brands
$brands = array();
$sql = "SELECT brand_id, brand_name FROM brands";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $brands[$row['brand_id']] = $row['brand_name'];
    }
}

// Fetch sizes
$sizes = array();
$sql = "SELECT size_id, size_name FROM size";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sizes[$row['size_id']] = $row['size_name'];
    }
}
?>

<form class="row g-3" action="" method="post" enctype="multipart/form-data">
    <!-- Populate with existing product data -->
    <div class="ec-vendor-upload-detail">
        <!-- Add image upload field -->
        <div class="col-md-6">
            <label class="form-label">Product Image</label>
            <input type="file" class="form-control" name="product_image" accept="image/*">
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Product name</label>
            <input type="text" class="form-control" name="product_name" value="<?php echo $productData['product_name']; ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Select Category</label>
            <select name="category_id" class="form-select">
                <?php foreach ($categories as $categoryId => $categoryName): ?>
                    <option value="<?php echo $categoryId; ?>" <?php echo ($productData['category_id'] == $categoryId) ? 'selected' : ''; ?>><?php echo $categoryName; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Select Brand</label>
            <select name="brand_id" class="form-select">
                <?php foreach ($brands as $brandId => $brandName): ?>
                    <option value="<?php echo $brandId; ?>" <?php echo ($productData['brand_id'] == $brandId) ? 'selected' : ''; ?>><?php echo $brandName; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Availability</label>
            <select name="availability" class="form-select">
                <option value="1" <?php echo ($productData['availability'] == '1') ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo ($productData['availability'] == '0') ? 'selected' : ''; ?>>No</option>
            </select>
        </div>
        <?php
// Fetch product sizes associated with the product
$productSizes = array();
$sql = "SELECT size_id FROM product_size WHERE product_id = '$productId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productSizes[] = $row['size_id'];
    }
}

// Fetch all available sizes
$sizes = array();
$sql = "SELECT size_id, size_name FROM size";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sizes[$row['size_id']] = $row['size_name'];
    }
}
?>

<div class="col-md-8 mb-25">
    <label class="form-label">Size</label>
    <div class="form-checkbox-box">
        <?php foreach ($sizes as $sizeId => $sizeName): ?>
            <div class='form-check form-check-inline'>
                <input type='checkbox' name='sizes[]' value='<?php echo $sizeId; ?>' <?php echo (in_array($sizeId, $productSizes)) ? 'checked' : ''; ?>>
                <label><?php echo $sizeName; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

        <div class="col-md-6">
            <label class="form-label">Price (In Tsh)</label>
            <input type="number" class="form-control" name="price" value="<?php echo $productData['price']; ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" value="<?php echo $productData['stock']; ?>">
        </div>
        <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" rows="4" name="description" value="<?php echo $productData['description']; ?>"></textarea>
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Code</label>
            <input type="text" class="form-control" name="code" value="<?php echo $productData['code']; ?>">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Update Product</button>
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