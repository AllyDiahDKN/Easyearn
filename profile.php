<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === false) {
    // User is logged in, redirect to dashboard.php
    header("Location: login.php");
    exit();
}
require_once 'db.php';
// Default user ID
$userID = $_SESSION['user_id'];
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
                    <div class="ec-vendor-dashboard-card">
                        <div class="ec-vendor-card-body">
                            <div class="row">
                                
                                <div class="col-lg-12">
                                    <div class="ec-vendor-upload-detail">
<?php
                                    // Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $mobile = $_POST['mobile'];
    $transferTo = $_POST['Transfer_to'];

    // Prepare SQL statement to update user information
    $sql = "UPDATE user SET first_name=?, last_name=?, Transfer_to=?, mobile=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters to the statement
    $stmt->bind_param("ssiii", $firstName, $lastName, $transferTo, $mobile, $userID);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to user profile page after successful update
       // header("Location: profile.php");
        //exit;
    } else {
        // Handle error message
        echo "Error updating user information: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch user details from the database
$sql = "SELECT * FROM user WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output data of the user
    $row = $result->fetch_assoc();
} else {
    // Handle error message if user not found
    echo "User not found.";
    exit; // Exit the script if user not found
}

// Close the statement
$stmt->close();

?>                       
             <div style="color: red;alignment: center;"><p><?php echo "Success updating user information."; ?></p></div>
            <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="col-md-6">
                <label for="FirstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row['first_name']; ?>" required>
            </div>
            <div class="col-md-6">
                <label for="LastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="LastName" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" disabled>
            </div>
            <div class="col-md-6">
                <label for="Mobile" class="form-label">Mobile Number</label>
                <input type="number" class="form-control" id="mobile" name="mobile" value="<?php echo htmlspecialchars($row['mobile']); ?>">
            </div>
            <div class="col-md-6">
                <label for="TransferTo" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($row['password']); ?>">
</div>
            <div class="col-md-6">
                <label for="TransferTo" class="form-label">Transfer To</label>
                <input type="number" class="form-control" id="Transfer_to" name="Transfer_to" value="<?php echo htmlspecialchars($row['Transfer_to']); ?>">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        
        <?php

// Close the database connection
$conn->close();
?>
                                    </div>
                                </div>
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