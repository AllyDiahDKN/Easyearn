<header class="ec-header">
        <!-- Ec Header Bottom  Start -->
        <div class="ec-header-bottom d-none d-lg-block">
            <div class="container position-relative">
                <div class="row">
                    <div class="ec-flex">
                        <!-- Ec Header Logo Start -->
                        <div class="align-self-center">
                            <div class="header-logo">
                                <a href="index.php"><img src="assets/images/logo/logo.png" alt="Site Logo" /><img
                                        class="dark-logo" src="assets/images/logo/dark-logo.png" alt="Site Logo"
                                        style="display: none;" /></a>
                            </div>
                        </div>
                        <!-- Ec Header Logo End -->

                      <!-- Ec Header Search Start -->
<div class="align-self-center">
    <div class="header-search">
        <form class="ec-btn-group-form" action="shop.php" method="GET">
            <input class="form-control ec-search-bar" name="search" placeholder="Search products..." type="text">
            <button class="submit" type="submit"><i class="fi-rr-search"></i></button>
        </form>
    </div>
</div>
<!-- Ec Header Search End -->


                        <!-- Ec Header Button Start -->
                        <div class="align-self-center">
                            <div class="ec-header-bottons">

                                <!-- Header User Start -->
                                <div class="ec-header-user dropdown">
                                    <button class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                            class="fi-rr-user"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is logged in, show links to dashboard and my profile
    echo '<li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>';
    echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
} else {
    // User is not logged in, show links to register and login
    echo '<li><a class="dropdown-item" href="register.php">Register</a></li>';
    echo '<li><a class="dropdown-item" href="login.php">Login</a></li>';
}
?>
                                    </ul>
                                </div>
                                <!-- Header User End -->
                                <!-- Header Cart Start -->
                                <?php
// Get user_id from the session
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Check if user is logged in
if ($userID) {
    // Query to get the number of items in the cart for the user
    $cartCountQuery = "SELECT COUNT(*) as itemCount FROM cart WHERE user_id = $userID AND status = 'In Cart'";
    
    // Execute the query
    $cartCountResult = $conn->query($cartCountQuery);

    if ($cartCountResult) {
        // Fetch the result
        $cartCountRow = $cartCountResult->fetch_assoc();

        // Get the number of items in the cart
        $numberOfItemsInCart = $cartCountRow['itemCount'];

        // Output the number of items in cart
        echo "<a href='#ec-side-cart' class='ec-header-btn ec-side-toggle'>
                <div class='header-icon'><i class='fi-rr-shopping-bag'></i></div>
                <span class='ec-header-count cart-count-lable'>$numberOfItemsInCart</span>
              </a>";
    } else {
        // Handle the error, e.g., display an error message
        echo "Error fetching cart count: " . $conn->error;
    }
} else {
    // If the user is not logged in, you might want to display a default link
    echo "<a href='#ec-side-cart' class='ec-header-btn ec-side-toggle'>
            <div class='header-icon'><i class='fi-rr-shopping-bag'></i></div>
            <span class='ec-header-count cart-count-lable'>0</span>
          </a>";
}
?>

                                <!-- Header Cart End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ec Header Button End -->
        <!-- Header responsive Bottom  Start -->
        <div class="ec-header-bottom d-lg-none">
            <div class="container position-relative">
                <div class="row ">

                    <!-- Ec Header Logo Start -->
                    <div class="col">
                        <div class="header-logo">
                            <a href="index.php"><img src="assets/images/logo/logo.png" alt="Site Logo" /><img
                                    class="dark-logo" src="assets/images/logo/dark-logo.png" alt="Site Logo"
                                    style="display: none;" /></a>
                        </div>
                    </div>
                    <!-- Ec Header Logo End -->
                    <!-- Ec Header Search Start -->
                    <div class="col">
                        <div class="header-search">
                             <form class="ec-btn-group-form" action="shop.php" method="GET">
            <input class="form-control ec-search-bar" name="search" placeholder="Search products..." type="text">
            <button class="submit" type="submit"><i class="fi-rr-search"></i></button>
        </form>
                        </div>
                    </div>
                    <!-- Ec Header Search End -->
                </div>
            </div>
        </div>
        <!-- Header responsive Bottom  End -->
        <!-- EC Main Menu Start -->
        <div id="ec-main-menu-desk" class="d-none d-lg-block sticky-nav">
            <div class="container position-relative">
                <div class="row">
                    <div class="col-md-12 align-self-center">
                        <div class="ec-main-menu">
                            <ul>
                                <li><a href="index.php">Home</a></li>
                                <li><a href="shop.php">Shop</a></li>
                                
                                <li><a href="under-maintenance.html">Blog</a>
                                </li>
                                <li><a href="contact-us.php">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ec Main Menu End -->
        <!-- ekka Mobile Menu Start -->
        <div id="ec-mobile-menu" class="ec-side-cart ec-mobile-menu">
            <div class="ec-menu-title">
                <span class="menu_title">Menu</span>
                <button class="ec-close">×</button>
            </div>
            <div class="ec-menu-inner">
                <div class="ec-menu-content">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="javascript:void(0)">Categories</a>
                            <ul class="sub-menu">
                                <?php
                                // Fetch category names starting from the 8th category
                                $query = "SELECT category_id, category_name FROM category"; // Assuming there are less than 100 categories

                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                                // Check if there are any categories
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $categoryId = $row['category_id'];
                                        $categoryName = $row['category_name'];
                                ?>
                                    <li><a href="shop.php?category_id=<?php echo $categoryId; ?>"><?php echo $categoryName; ?></a></li>

                                <?php
                                    }
                                } else {
                                    // Handle the case where there are no categories
                                    echo '<li><a href="#">No categories found</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li><a href="shop.php">Shop</a></li>
                        <li><a href="under-maintenance.html">Blog</a></li>
                        <li><a href="contact.php">Support</a></li>
                    </ul>
                </div>
                <div class="header-res-lan-curr">
                    <div class="header-top-lan-curr">
                        <!-- Language Start -->
                        <div class="header-top-lan dropdown">
                            <button class="dropdown-toggle text-upper" data-bs-toggle="dropdown">Language <i
                                    class="ecicon eci-caret-down" aria-hidden="true"></i></button>
                            <ul class="dropdown-menu">
                                <li class="active"><a class="dropdown-item" href="#">English</a></li>
                                <li><a class="dropdown-item" href="#">Kiswahili</a></li>
                            </ul>
                        </div>
                        <!-- Language End -->
                    </div>
                    <!-- Social Start -->
                    <div class="header-res-social">
                        <div class="header-top-social">
                            <ul class="mb-0">
                                <li class="list-inline-item"><a class="hdr-facebook" href="#"><i
                                            class="ecicon eci-facebook"></i></a></li>
                                <li class="list-inline-item"><a class="hdr-twitter" href="#"><i
                                            class="ecicon eci-twitter"></i></a></li>
                                <li class="list-inline-item"><a class="hdr-instagram" href="#"><i
                                            class="ecicon eci-instagram"></i></a></li>
                                <li class="list-inline-item"><a class="hdr-linkedin" href="#"><i
                                            class="ecicon eci-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Social End -->
                </div>
            </div>
        </div>
        <!-- ekka mobile Menu End -->
    </header>