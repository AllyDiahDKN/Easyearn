<?php
session_start();
require_once '../db.php';
// Check if the order ID is provided in the URL
if(isset($_GET['order_id'])) {
    // Retrieve the order ID from the URL
    $orderId = $_GET['order_id'];

    // Now you can use the $orderId variable to fetch and display details of the specific order
    // For example, you can query your database to get order details based on this ID
    // Then display the order details on the page
    echo "Order ID: " . $orderId;
} else {
    // If order ID is not provided in the URL, display an error message or handle the situation accordingly
    echo "Order ID not found in the URL.";
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

	<!-- Ekka CSS -->
	<link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

	<!-- FAVICON -->
	<link href="assets/img/favicon.png" rel="shortcut icon" />
</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">

	<!-- WRAPPER -->
	<?php include 'header.php';?>

			<!-- CONTENT WRAPPER -->
			<?php
// Function to fetch customer details based on order number
function getCustomerDetails($conn, $orderNumber) {
    $sql = "SELECT c.first_name, c.last_name, c.mobile, c.email, c.city, c.country
            FROM customers c
            INNER JOIN orders o ON c.id = o.customer_id
            WHERE o.order_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to fetch seller details based on order number
function getSellerDetails($conn, $orderNumber) {
    $sql = "SELECT u.first_name, u.last_name, u.mobile, u.username
            FROM user u
            INNER JOIN orders o ON o.user_id = u.user_id
            WHERE o.order_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to fetch product details based on order number
function getProductDetails($conn, $orderNumber) {
    $sql = "SELECT p.product_id, p.product_name, s.size_name, p.price, c.quantity, p.product_image
            FROM cart c
            INNER JOIN products p ON c.product_id = p.product_id
            INNER JOIN size s ON p.size_id = s.size_id
            WHERE c.order_id = ? AND c.status = 'ordered'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderNumber);
    $stmt->execute();
    return $stmt->get_result();
}

// Retrieve the order ID from the URL parameter
$orderID = $_GET['order_id'];

// Fetch customer details
$customerDetails = getCustomerDetails($conn, $orderID);

// Fetch seller details
$sellerDetails = getSellerDetails($conn, $orderID);

// Fetch product details
$productDetails = getProductDetails($conn, $orderID);

// Fetch approval status
$stmt = $conn->prepare("SELECT approval, payment, delivery FROM orders WHERE order_number = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$stmt->bind_result($approval, $payment, $delivery);
$stmt->fetch();
$stmt->close();
?>
    <div class="ec-content-wrapper">
        <div class="content">
            <div class="breadcrumb-wrapper breadcrumb-wrapper-2">
                <h1>Order Detail</h1>
                <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>Orders
                </p>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="ec-odr-dtl card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2 class="ec-odr">Order Detail<br>
                                <span class="small">Order ID: <?php echo $orderID; ?></span>
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Customer Details -->
                                <div class="col-xl-3 col-lg-6">
                                    <address class="info-grid">
                                        <div class="info-title"><strong>Customer:</strong></div><br>
                                        <div class="info-content">
                                            <?php if ($customerDetails) : ?>
                                                <strong><?php echo $customerDetails['first_name'] . ' ' . $customerDetails['last_name']; ?></strong><br>
                                                <?php echo $customerDetails['mobile']; ?><br>
                                                <?php echo $customerDetails['email']; ?><br>
                                                <?php echo $customerDetails['city'] . ', ' . $customerDetails['country']; ?><br>
                                            <?php else : ?>
                                                No customer found for the given order number.
                                            <?php endif; ?>
                                        </div>
                                    </address>
                                </div>

                                <!-- Seller Details -->
                                <div class="col-xl-3 col-lg-6">
                                    <address class="info-grid">
                                        <div class="info-title"><strong>Seller:</strong></div><br>
                                        <div class="info-content">
                                            <?php if ($sellerDetails) : ?>
                                                <strong><?php echo $sellerDetails['first_name'] . ' ' . $sellerDetails['last_name']; ?></strong><br>
                                                <?php echo $sellerDetails['mobile']; ?><br>
                                                <?php echo $sellerDetails['username']; ?><br>
                                            <?php else : ?>
                                                No seller found for the given order number.
                                            <?php endif; ?>
                                        </div>
                                    </address>
                                </div>

                                <!-- Approval Status and Buttons -->
                                <div class="col-xl-6 col-lg-6">
                                    <address class="info-grid">
                                        <div class="info-title"><strong>Status:</strong></div><br>
                                        <div class="info-content">
                                            <button type="button" class="<?php echo $approval === 'Not Approved' ? 'btn btn-primary' : 'btn'; ?>" id="not-approved-btn" data-order-id="<?php echo $orderID; ?>">Not Approved</button> |
                                            <button type="button" class="<?php echo $approval === 'Pending' ? 'btn btn-primary' : 'btn'; ?>" id="pending-btn" data-order-id="<?php echo $orderID; ?>">Pending</button> |
                                            <button type="button" class="<?php echo $approval === 'Approved' ? 'btn btn-primary' : 'btn'; ?>" id="approved-btn" data-order-id="<?php echo $orderID; ?>">Approved</button>

                                            <br>
                                            <button type="button" class="<?php echo $payment === 'Not Paid' ? 'btn btn-primary' : 'btn'; ?>" id="not-paid-btn" data-order-id="<?php echo $orderID; ?>">Not Paid</button> |
                                            <button type="button" class="<?php echo $payment === 'Paid' ? 'btn btn-primary' : 'btn'; ?>" id="paid-btn" data-order-id="<?php echo $orderID; ?>">Paid</button>
                                            <br>

                                            <button type="button" class="btn btn-primary">Delivery Pending</button> |
                                            <button type="button" class="btn">Delivered</button>
                                        </div>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="tbl-title">PRODUCT SUMMARY</h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped o-tbl">
                                            <thead>
                                                <tr class="line">
                                                    <td><strong>#</strong></td>
                                                    <td class="text-center"><strong>IMAGE</strong></td>
                                                    <td class="text-center"><strong>PRODUCT</strong></td>
                                                    <td class="text-center"><strong>PRICE/UNIT</strong></td>
                                                    <td class="text-right"><strong>QUANTITY</strong></td>
                                                    <td class="text-right"><strong>SUBTOTAL</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($productDetails->num_rows > 0) {
                                                    while ($row = $productDetails->fetch_assoc()) {
                                                        $productId = $row['product_id'];
                                                        $productName = $row['product_name'];
                                                        $size = $row['size_name'];
                                                        $price = $row['price'];
                                                        $image = $row['product_image'];
                                                        $quantity = $row['quantity'];
                                                        $totalPrice = $price * $quantity;
                                                ?>
                                                        <tr>
                                                            <td><?php echo $productId; ?></td>
                                                            <td><img class="product-img" src="../assets/images/product-image/<?php echo $image; ?>" alt="" /></td>
                                                            <td><strong><?php echo $productName; ?></strong><br>Size: <?php echo $size; ?></td>
                                                            <td class="text-center"><?php echo $price; ?></td>
                                                            <td class="text-center"><?php echo $quantity; ?></td>
                                                            <td class="text-right"><?php echo $totalPrice; ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    // If no products are found in the cart for the given order number
                                                    echo "<tr><td colspan='6'>No products found in the cart for this order</td></tr>";
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-right"><strong>Sub-Total</strong></td>
                                                    <td class="text-right"><strong>$2,400.00</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-right"><strong>Discount</strong></td>
                                                    <td class="text-right"><strong>$2,400.00</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="text-right"><strong>Total</strong></td>
                                                    <td class="text-right"><strong>$2,400.00</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
<script type="text/javascript">
$(document).ready(function() {
    // Event handler for Not Approved button
    $('#not-approved-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateStatus('Not Approved', orderId, 'approval');
    });

    // Event handler for Pending button
    $('#pending-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateStatus('Pending', orderId, 'approval');
    });

    // Event handler for Approved button
    $('#approved-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateStatus('Approved', orderId, 'approval');
    });

    // Event handler for Not Paid button
    $('#not-paid-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateStatus('Not Paid', orderId, 'payment');
    });

    // Event handler for Paid button
    $('#paid-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateStatus('Paid', orderId, 'payment');
    });

    // Function to send AJAX request to update status
    function updateStatus(status, orderId, type) {
        $.ajax({
            url: 'update_status.php', // Update the URL according to your backend script
            type: 'POST',
            data: { status: status, order_id: orderId, type: type }, // Include the status and type of update
            success: function(response) {
                // Reload the page to reflect the changes
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }
});
</script>
</script>
	<!-- Option Switcher -->
	<script src="assets/plugins/options-sidebar/optionswitcher.js"></script>

	<!-- Ekka Custom -->
	<script src="assets/js/ekka.js"></script>
</body>
</html>
<?php
$conn->close();
?>