   <div class="ec-nav-toolbar">
        <div class="container">
            <div class="ec-nav-panel">
                <div class="ec-nav-panel-icons">
                    <a href="#ec-mobile-menu" class="navbar-toggler-btn ec-header-btn ec-side-toggle"><i
                            class="fi-rr-menu-burger"></i></a>
                </div>
                <div class="ec-nav-panel-icons">
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
        echo "<a href='#ec-side-cart' class='toggle-cart ec-header-btn ec-side-toggle'>
                <i class='fi-rr-shopping-bag'></i>
                <span class='ec-cart-noti ec-header-count cart-count-lable'>$numberOfItemsInCart</span>
              </a>";
    } else {
        // Handle the error, e.g., display an error message
        echo "Error fetching cart count: " . $conn->error;
    }
} else {
    // If the user is not logged in, you might want to display a default link
    echo "<a href='#ec-side-cart' class='toggle-cart ec-header-btn ec-side-toggle'>
                <i class='fi-rr-shopping-bag'></i>
                <span class='ec-cart-noti ec-header-count cart-count-lable'>0</span>
              </a>";
}
?> 
                  
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="index.php" class="ec-header-btn"><i class="fi-rr-home"></i></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="contact.php" class="ec-header-btn"><i class="fi-rr-headset"></i></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="dashboard.php" class="ec-header-btn"><i class="fi-rr-user"></i></a>
                </div>

            </div>
        </div>
    </div>