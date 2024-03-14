<?php 
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}
require_once 'db.php';
?>
 <!DOCTYPE html>
 <html lang="en">
 
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="x-ua-compatible" content="ie=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
 
     <title>Easy Earn Tanzania - Seller Dashboard.</title>
     <meta name="keywords"
         content="apparel, catalog, clean, ecommerce, ecommerce HTML, electronics, fashion, html eCommerce, html store, minimal, multipurpose, multipurpose ecommerce, online store, responsive ecommerce template, shops" />
     <meta name="description" content="Best ecommerce html template for single and multi vendor store.">
     <meta name="author" content="ashishmaraviya">
 
     <!-- site Favicon -->
     <link rel="icon" href="assets/images/favicon/favicon.png" sizes="32x32" />
     <link rel="apple-touch-icon" href="assets/images/favicon/favicon.png" />
     <meta name="msapplication-TileImage" content="assets/images/favicon/favicon.png" />
 
     <!-- css Icon Font -->
     <link rel="stylesheet" href="assets/css/vendor/ecicons.min.css" />
 
     <!-- css All Plugins Files -->
     <link rel="stylesheet" href="assets/css/plugins/animate.css" />
     <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/countdownTimer.css" />
     <link rel="stylesheet" href="assets/css/plugins/slick.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/bootstrap.css" />
 
     <!-- Main Style -->
     <link rel="stylesheet" href="assets/css/style.css" />
     <link rel="stylesheet" href="assets/css/responsive.css" />
 
     <!-- Background css -->
     <link rel="stylesheet" id="bg-switcher-css" href="assets/css/backgrounds/bg-4.css">
 </head>
<body class="shop_page">

    <!-- Header start  -->
    <?php include 'header.php';?>
    <!-- Header End  -->

    <!-- ekka Cart Start -->
    <?php include 'cart_modal.php';?>
    <!-- ekka Cart End -->

    <!-- Ec breadcrumb start -->
    <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row ec_breadcrumb_inner">
                        <div class="col-md-6 col-sm-12">
                            <h2 class="ec-breadcrumb-title">Seller Dashboard</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <!-- ec-breadcrumb-list start -->
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="ec-breadcrumb-item active">Dashboard</li>
                            </ul>
                            <!-- ec-breadcrumb-list end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ec breadcrumb end -->

    <!-- Vendor dashboard section -->
    <section class="ec-page-content ec-vendor-dashboard section-space-p">
        <div class="container">
            <div class="row">
                <!-- Sidebar Area Start -->
                <div class="ec-shop-leftside ec-vendor-sidebar col-lg-3 col-md-12">
                    <div class="ec-sidebar-wrap ec-border-box">
                        <!-- Sidebar Category Block -->
                        <div class="ec-sidebar-block">
                            <div class="ec-vendor-block">
                                <div class="ec-vendor-block-bg"></div>
                                <div class="ec-vendor-block-detail">
                                    <img class="v-img" src="assets/images/vendor/5.jpg" alt="vendor image">
<?php
// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Fetch user details from the database based on the user ID
    $userQuery = "SELECT * FROM user WHERE user_id = $userId";
    $userResult = $conn->query($userQuery);

    if ($userResult && $userResult->num_rows > 0) {
        // User found, get user details
        $userData = $userResult->fetch_assoc();
        $firstName = $userData['first_name'];
        $lastName = $userData['last_name'];

        // Display user's first and last name
        echo "<h5>$firstName $lastName</h5>";
    } else {
        // Error: User not found
        echo "<p>Error: User not found</p>";
    }
} else {
    // User is not logged in, handle as needed
    echo "<p>Error: User not logged in</p>";
}
?>
                                </div>
                                                                <?php include 'dashboard_menu.php';?>

                            </div>
                         
                        </div>
                    </div>
                </div>
                <div class="ec-shop-rightside col-lg-9 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-6"> 
    <div class="ec-vendor-dashboard-sort-card color-blue">
        <h5>Sales</h5>
        <?php
        $userId = $_SESSION['user_id'];

        // Query to get total sales for the user
        $salesQuery = "SELECT SUM(total) AS total_sales FROM orders WHERE user_id = $userId AND payment = 'paid'";

        // Execute the sales query
        $salesResult = $conn->query($salesQuery);

        if ($salesResult) {
            // Fetch the result as an associative array
            $salesRow = $salesResult->fetch_assoc();

            // Get the total sales
            $totalSales = $salesRow['total_sales'];

            // Output the total sales with comma formatting
            echo "<h3>" . number_format($totalSales) . "Tsh</h3>";
        } else {
            // Handle the sales query error
            echo "<h3>Error executing sales query: " . $conn->error . "</h3>";
        }
        ?>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="ec-vendor-dashboard-sort-card color-pink">
        <h5>Earnings</h5>
        <?php
        // Query to get commission for the user
        $commissionQuery = "SELECT commission FROM user WHERE user_id = $userId";

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

            // Output the earnings with comma formatting
            echo "<h3>" . number_format($earnings) . "Tsh</h3>";
        } else {
            // Handle the commission query error
            echo "<h3>Error executing commission query: " . $conn->error . "</h3>";
        }
        ?>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="ec-vendor-dashboard-sort-card color-green">
        <h5>Paid</h5>
        <?php
        $paidQuery = "SELECT SUM(payment) AS paid FROM commission WHERE user_id = $userId";

        // Execute the paid query
        $paidResult = $conn->query($paidQuery);

        if ($paidResult) {
            // Fetch the result as an associative array
            $paidRow = $paidResult->fetch_assoc();

            // Get the total paid
            $paid = $paidRow['paid'];

            // Output the paid amount with comma formatting
            echo "<h3>" . number_format($paid ?? 0) . "Tsh</h3>";
        } else {
            // Handle the paid query error
            echo "<h3>Error executing paid query: " . $conn->error . "</h3>";
        }
        ?>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="ec-vendor-dashboard-sort-card color-orange">
        <h5>Pending</h5>
        <?php
        // Calculate pending amount
        $pending = $earnings - ($paid ?? 0);

        // Output the pending amount with comma formatting
        echo "<h3>" . number_format(($pending >= 0 ? $pending : 0)) . "Tsh</h3>";
        ?>
    </div>
</div>

                    </div>
                    <div class="ec-vendor-dashboard-card space-bottom-30">
                        <div class="ec-vendor-card-header">
                            <h5>Sales</h5>
                        </div>
                        <div class="ec-vendor-card-body">
                            <div class="ec-vendor-card-table">
  <?php

// SQL query to retrieve orders of the user with customer name
$orderQuery = "SELECT o.order_number AS order_id, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, o.total, u.commission, o.approval
               FROM orders o
               INNER JOIN customers c ON o.customer_id = c.id
               INNER JOIN user u ON o.user_id = u.user_id
               WHERE o.user_id = $userId";

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
        echo '<table class="table ec-table">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Total</th>
                        <th scope="col">Commission</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
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
                    <th scope="row">' . $orderId . '</th>
                    <td>' . $customerName . '</td>
                    <td>' . $total . '</td>
                    <td>' . $commission . '</td>
                    <td>' . $status . '</td>
                    <td> 
                        <a href="order_invoice.php?order_number=' . $orderId . '" class="btn btn-primary" title="View Order"><i class="fi-rr-eye"></i></a>
                        <button class="btn btn-primary" onclick="sendWhatsAppMessage('. $orderId .')"><i class="fi-rr-info"></i></button>
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
    </section>
    <!-- End Vendor dashboard section -->

    <!-- Footer Start -->
    <?php include 'footer.php';?>
    <!-- Footer Area End -->

    <!-- Modal -->
    <?php include 'cart_modal.php';?>
    <!-- Modal end -->

    <!-- Footer navigation panel for responsive display -->
    <?php include 'footer_menu.php';?>
    <!-- Footer navigation panel for responsive display end -->

    <!-- Cart Floating Button -->
    <div class="ec-cart-float">
        <a href="#ec-side-cart" class="ec-header-btn ec-side-toggle">
            <div class="header-icon"><i class="fi-rr-shopping-basket"></i>
            </div>
            <span class="ec-cart-count cart-count-lable">3</span>
        </a>
    </div>
    <!-- Cart Floating Button end -->

    <!-- Whatsapp -->
    <?php include 'whatsapp.php';?>

    <!-- Whatsapp end -->

   <!-- Vendor JS -->
   <script src="assets/js/vendor/jquery-3.5.1.min.js"></script>
   <script src="assets/js/vendor/popper.min.js"></script>
   <script src="assets/js/vendor/bootstrap.min.js"></script>
   <script src="assets/js/vendor/jquery-migrate-3.3.0.min.js"></script>
   <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>

   <!--Plugins JS-->
   <script src="assets/js/plugins/swiper-bundle.min.js"></script>
   <script src="assets/js/plugins/nouislider.js"></script>
   <script src="assets/js/plugins/countdownTimer.min.js"></script>
   <script src="assets/js/plugins/scrollup.js"></script>
   <script src="assets/js/plugins/jquery.zoom.min.js"></script>
   <script src="assets/js/plugins/slick.min.js"></script>
   <script src="assets/js/plugins/infiniteslidev2.js"></script>
   <script src="assets/js/vendor/jquery.magnific-popup.min.js"></script>
   <script src="assets/js/plugins/chart.min.js"></script>
   <script src="assets/js/plugins/jquery.sticky-sidebar.js"></script>
   
   <!-- Main Js -->
   <script src="assets/js/chart-main.js"></script>
   <script src="assets/js/main.js"></script>
   <script>
function sendWhatsAppMessage(orderNumber) {
    // Construct the WhatsApp message
    var message = "Hi, I need assistance with order number " + orderNumber;

    // Encode the message for a WhatsApp URL
    var encodedMessage = encodeURIComponent(message);

    // Construct the WhatsApp URL
    var whatsappURL = "https://api.whatsapp.com/send?phone=+255785599554&text=" + encodedMessage;

    // Open the WhatsApp URL in a new tab
    window.open(whatsappURL, "_blank");
}
</script>


</body>
</html>
<?php
// Close the database connection
$conn->close();
?>