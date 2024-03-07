<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}

require 'db.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Query to count rows in the cart for the user where cart status is "In Cart"
$countQuery = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ? AND status = 'In Cart'";
$stmt = $conn->prepare($countQuery);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($cartCount);
    $stmt->fetch();

    if ($cartCount === 0) {
        // Redirect to the shop page
        header("Location: shop.php");
        exit(); // Ensure no further output is sent
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where the statement preparation fails
    echo "Error preparing statement: " . $conn->error;
}
?>


 <!DOCTYPE html>
 <html lang="en">
    <head>
     <meta charset="UTF-8">
     <meta http-equiv="x-ua-compatible" content="ie=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
 
     <title>Ekka - Ecommerce HTML Template + Admin Dashboard.</title>
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
<body class="checkout_page">

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
                            <h2 class="ec-breadcrumb-title">Checkout</h2>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <!-- ec-breadcrumb-list start -->
                            <ul class="ec-breadcrumb-list">
                                <li class="ec-breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="ec-breadcrumb-item active">Checkout</li>
                            </ul>
                            <!-- ec-breadcrumb-list end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ec breadcrumb end -->

    <!-- Ec checkout page -->
    <section class="ec-page-content section-space-p">
        <div class="container">
            <div class="row">
                <div class="ec-checkout-leftside col-lg-8 col-md-12 ">
                    <!-- checkout content Start -->
                    <!-- Ec Tabs page -->
                <div class="ec-tab-wrapper ec-tab-wrapper-1">
                    <div class="ec-single-pro-tab-wrapper">
                        <div class="ec-single-pro-tab-nav">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#ec-spt-nav-details" role="tab" aria-controls="ec-spt-nav-details" aria-selected="true">Existing Customer</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-info"
                                         role="tab" aria-controls="ec-spt-nav-info" aria-selected="false">New Customer</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content  ec-single-pro-tab-content">
                            <div id="ec-spt-nav-details" class="tab-pane fade show active">
                                <div class="ec-single-pro-tab-desc">
                                    <div class="ec-checkout-wrap margin-bottom-30 padding-bottom-3">
                                <div class="ec-checkout-block ec-check-bill">
                                    <div class="ec-bl-block-content">
                                        <div class="ec-check-bill-form">
<form action="confirm_order_old_customer.php" method="post">
                                                <span class="ec-bill-wrap">
                                                    <label>Choose Customer *</label>
                                                    <span class="ec-bl-select-inner">
<select name="ec_select_country" id="ec-select-country" class="ec-bill-select" required>
                                                            <option selected disabled>Choose customer Name</option>
<?php
// Assuming $userId contains the user ID
$userId = $_SESSION['user_id'];

// SQL query to fetch customer details for a specific user
$customerQuery = "SELECT id, first_name, last_name, mobile FROM customers WHERE user_id = $userId";

// Execute the query
$result = $conn->query($customerQuery);

// Check if the query was successful
if ($result) {
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetch the result as an associative array
        while ($row = $result->fetch_assoc()) {
            $customerId = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $mobile = $row['mobile'];

            // Output the customer details, you can customize this based on your needs
            echo "<option value='$customerId'>$first_name $last_name - $mobile</option>";
        }
    } else {
        // No existing customers found
        echo "<option value='' disabled>No existing customers found</option>";
    }
} else {
    // Handle the query error
    echo "Error executing customer query: " . $conn->error;
}

?>

                                                        </select>
                                                    </span>
                                                </span>
                                                <span class="ec-check-login-wrap ec-check-login-btn">
                                <input type="submit" value="Confirm Order for your Customer" class="btn btn-primary" ></input>
                            </span>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                                </div>
                            </div>
                            <div id="ec-spt-nav-info" class="tab-pane fade">
                                <div class="ec-single-pro-tab-moreinfo">
                                    <div class="ec-checkout-wrap margin-bottom-30 padding-bottom-3">
                                <div class="ec-checkout-block ec-check-bill">
                                    <div class="ec-bl-block-content">
                                        <div class="ec-check-bill-form">
                                            <form action="confirm_order_new_customer.php" method="post">
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>First Name*</label>
                                                    <input type="text" name="firstname"
                                                        placeholder="Enter your first name" required />
                                                </span>
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>Last Name*</label>
                                                    <input type="text" name="lastname"
                                                        placeholder="Enter your last name" required />
                                                </span>        
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>Mobile *</label>
                                                    <input type="text" name="mobile" placeholder="+255..." required/>
                                                </span>
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>Email</label>
                                                    <input type="email" name="email" placeholder="Email(optional)" />
                                                </span>
                                                <span class="ec-bill-wrap">
                                                    <label>Address *</label>
                                                    <input type="text" name="address" placeholder="Address " required/>
                                                </span>
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>City *</label>
                                                    <input type="text" name="city" placeholder="Customer's City" required/>
                                                </span>
                                                <span class="ec-bill-wrap ec-bill-half">
                                                    <label>Country *</label>
                                                    <span class="ec-bl-select-inner">
                                                        <select name="country" id="ec-country"
                                                            class="ec-bill-select" required>
                                                            <option selected disabled>Country</option>
 <?php
// Fetch countries from the database
$sql = "SELECT name FROM countries";
$result = $conn->query($sql);

// Check if there are countries
if ($result->num_rows > 0) {
    // Output the options
    while ($row = $result->fetch_assoc()) {
        $countryName = $row['name'];
        echo "<option value=\"$countryName\">$countryName</option>";
    }
} else {
    echo "<option value=\"\">No countries found</option>";
}
?>                                                            
                                                        </select>
                                                    </span>
                                                </span>
                                                 <span class="ec-check-login-wrap ec-check-login-btn">
                                <input type="submit" value="Confirm Order for your Customer" class="btn btn-primary" ></input>
                            </span>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!--cart content End -->
                </div>
                <!-- Sidebar Area Start -->
                
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    <?php include 'footer.php';?>
    <!-- Footer Area End -->

    <!-- Modal -->
    <div class="modal fade" id="ec_quickview_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close qty_close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <!-- Swiper -->
                            <div class="qty-product-cover">
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_1.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_2.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_3.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_4.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_5.jpg" alt="">
                                </div>
                            </div>
                            <div class="qty-nav-thumb">
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_1.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_2.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_3.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_4.jpg" alt="">
                                </div>
                                <div class="qty-slide">
                                    <img class="img-responsive" src="assets/images/product-image/3_5.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="quickview-pro-content">
                                <h5 class="ec-quick-title"><a href="product-left-sidebar.html">Handbag leather purse for women</a>
                                </h5>
                                <div class="ec-quickview-rating">
                                    <i class="ecicon eci-star fill"></i>
                                    <i class="ecicon eci-star fill"></i>
                                    <i class="ecicon eci-star fill"></i>
                                    <i class="ecicon eci-star fill"></i>
                                    <i class="ecicon eci-star"></i>
                                </div>

                                <div class="ec-quickview-desc">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                    since the 1500s,</div>
                                <div class="ec-quickview-price">
                                    <span class="old-price">$100.00</span>
                                    <span class="new-price">$80.00</span>
                                </div>

                                <div class="ec-pro-variation">
                                    <div class="ec-pro-variation-inner ec-pro-variation-color">
                                        <span>Color</span>
                                        <div class="ec-pro-color">
                                            <ul class="ec-opt-swatch">
                                                <li><span style="background-color:#696d62;"></span></li>
                                                <li><span style="background-color:#d73808;"></span></li>
                                                <li><span style="background-color:#577023;"></span></li>
                                                <li><span style="background-color:#2ea1cd;"></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="ec-pro-variation-inner ec-pro-variation-size ec-pro-size">
                                        <span>Size</span>
                                        <div class="ec-pro-variation-content">
                                            <ul class="ec-opt-size">
                                                <li class="active"><a href="#" class="ec-opt-sz"
                                                        data-tooltip="Small">S</a></li>
                                                <li><a href="#" class="ec-opt-sz" data-tooltip="Medium">M</a></li>
                                                <li><a href="#" class="ec-opt-sz" data-tooltip="Large">X</a></li>
                                                <li><a href="#" class="ec-opt-sz" data-tooltip="Extra Large">XL</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="ec-quickview-qty">
                                    <div class="qty-plus-minus">
                                        <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                                    </div>
                                    <div class="ec-quickview-cart ">
                                        <button class="btn btn-primary"><i class="fi-rr-shopping-basket"></i> Add To Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="assets/js/plugins/countdownTimer.min.js"></script>
    <script src="assets/js/plugins/scrollup.js"></script>
    <script src="assets/js/plugins/jquery.zoom.min.js"></script>
    <script src="assets/js/plugins/slick.min.js"></script>
    <script src="assets/js/plugins/infiniteslidev2.js"></script>
    <script src="assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/plugins/jquery.sticky-sidebar.js"></script>
    
    <!-- Main Js -->
    <script src="assets/js/vendor/index.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
<?php
// Close the database connection if needed
$conn->close();
?>