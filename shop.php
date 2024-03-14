<?php 
session_start();

require 'db.php';
include 'metatag.php';

?>
 <!DOCTYPE html>
 <html lang="en">
    <head>
             <?php generateMetaTags('shop'); ?>

     <!-- css Icon Font -->
     <link rel="stylesheet" href="assets/css/vendor/ecicons.min.css" />
 
     <!-- css All Plugins Files -->
     <link rel="stylesheet" href="assets/css/plugins/animate.css" />
     <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/countdownTimer.css" />
     <link rel="stylesheet" href="assets/css/plugins/slick.min.css" />
     <link rel="stylesheet" href="assets/css/plugins/bootstrap.css" />
     <link rel="stylesheet" href="assets/css/plugins/nouislider.css" />
 
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

<?php
// Handle form submission and filter products
$selectedCategories = isset($_GET['category']) ? $_GET['category'] : array();
$selectedBrands = isset($_GET['brand']) ? $_GET['brand'] : array();
$selectedSizes = isset($_GET['size']) ? $_GET['size'] : array();

// Generate the WHERE clause for filtering
$whereClause = "WHERE availability = 1"; // Show only available products

// Search logic
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause .= $whereClause ? " AND product_name LIKE '%$searchQuery%'" : "WHERE product_name LIKE '%$searchQuery%'";

if (!empty($selectedCategories)) {
    $categoryCondition = "category_id IN (" . implode(',', $selectedCategories) . ")";
    $whereClause .= $whereClause ? " AND $categoryCondition" : $categoryCondition;
}

if (!empty($selectedBrands)) {
    $brandCondition = "brand_id IN (" . implode(',', $selectedBrands) . ")";
    $whereClause .= $whereClause ? " AND $brandCondition" : $brandCondition;
}

// Join the product_size table to get products based on selected sizes
if (!empty($selectedSizes)) {
    $sizeCondition = "p.product_id IN (
        SELECT ps.product_id FROM product_size ps 
        WHERE ps.size_id IN (" . implode(',', $selectedSizes) . ")
    )";
    $whereClause .= $whereClause ? " AND $sizeCondition" : $sizeCondition;
}


// Pagination logic
$productsPerPage = 16;
// Change this line
$sqlCount = "SELECT COUNT(DISTINCT p.product_id) as total FROM products p 
             LEFT JOIN product_size ps ON p.product_id = ps.product_id
             $whereClause";
$resultCount = $conn->query($sqlCount);

if (!$resultCount) {
    die("SQL Count Error: " . $conn->error);
}

$rowCount = $resultCount->fetch_assoc();
$totalProducts = $rowCount['total'];

$totalPages = ceil($totalProducts / $productsPerPage);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

$sql = "SELECT DISTINCT p.* FROM products p 
        LEFT JOIN product_size ps ON p.product_id = ps.product_id
        $whereClause 
        LIMIT $offset, $productsPerPage";

$result = $conn->query($sql);
?>


    <!-- Ec Shop page -->
    <section class="ec-page-content section-space-p">
        <div class="container">
            <div class="row">
                <!-- Shop content Start -->
                <div class="ec-shop-rightside col-lg-9 col-md-12 order-lg-last order-md-first margin-b-30">
                    <div class="shop-pro-content">
                        <div class="shop-pro-inner">
                            <div class="row">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $productId = $row["product_id"];
                                        $productName = $row["product_name"];
                                        $productImage = $row["product_image"];
                                        $productDescription = $row["description"];
                                        $productPrice = $row["price"];
                                        $sizeId = $row["size_id"];

                                        echo "<div class='col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content'>";
                                        echo "<div class='ec-product-inner'>";
                                        echo "<div class='ec-pro-image-outer'>";
                                        echo "<div class='ec-pro-image'>";
                                        echo "<a href='#' class='image' data-bs-toggle='modal' data-bs-target='#productModal$productId'>";
                                        echo "<img class='main-image' src='assets/images/product-image/$productImage' alt='$productName' />";
                                        echo "</a>";
                                        echo "<a href='#' class='quickview' data-link-action='quickview' title='Quick view' data-bs-toggle='modal' data-bs-target='#productModal$productId'><i class='fi-rr-eye'></i></a>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<div class='ec-pro-content'>";
                                        echo "<h5 class='ec-pro-title'><a href='#' class='quickview' data-link-action='quickview' title='Quick view' data-bs-toggle='modal' data-bs-target='#productModal$productId'>$productName</a></h5>";
                                        echo "<div class='ec-pro-list-desc'>$productDescription</div>";
                                        echo "<span class='ec-price'>";
                                        echo "<span class='new-price'>Tsh. ".number_format($productPrice)."</span>";
                                        echo "</span>";
                                        // Add more HTML as needed
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";

                                        // Product Modal
                                        echo "<div class='modal fade' id='productModal$productId' tabindex='-1' role='dialog'>";
                                        echo "<div class='modal-dialog modal-dialog-centered' role='document'>";
                                        echo "<div class='modal-content'>";
                                        echo "<button type='button' class='btn-close qty_close' data-bs-dismiss='modal' aria-label='Close'></button>";
                                        echo "<div class='modal-body'>";
                                        echo "<div class='row'>";
                                        echo "<div class='col-md-5 col-sm-12 col-xs-12'>";
                                        echo "<div class='qty-product-cover'>";
                                        echo "<div class='qty-slide'>";
                                        echo "<img class='img-responsive' src='assets/images/product-image/$productImage' alt=''>";
                                        echo "</div>";
                                        // Add more images if needed
                                        echo "</div>";
                                        // Add thumbnail navigation if needed
                                        echo "</div>";
                                        echo "<div class='col-md-7 col-sm-12 col-xs-12'>";
                                        echo "<div class='quickview-pro-content'>";
                                        echo "<h5 class='ec-quick-title'>$productName</h5>";
                                        echo "<div class='ec-quickview-desc'>$productDescription</div>";
  // Example query to check available sizes for a specific product
    $availableSizesQuery = "SELECT s.size_id, s.size_name
                            FROM size s
                            JOIN product_size ps ON s.size_id = ps.size_id
                            WHERE ps.product_id = $productId";

    $availableSizesResult = $conn->query($availableSizesQuery);

    echo "<div class='ec-quickview-price'>";
    echo "<span class='new-price'>$productPrice</span>";
    echo "</div>";

echo "<div class='ec-pro-variation'>";
echo "<div class='ec-pro-variation-inner ec-pro-variation-size ec-pro-size'>";
echo "<div class='ec-pro-variation-content'>";
echo "<span class='ec-register-wrap ec-register-half'>";


if ($availableSizesResult && $availableSizesResult->num_rows > 0) {
    // If sizes are available, display the size selection dropdown
    echo "<label>Choose size : </label>";
echo "<span class='ec-rg-select-inner'>";
echo "<form action='add_to_cart.php' method='post'>";
echo "<input type='hidden' name='product_id' value='$productId'>";
    echo "<select name='selected_size' class='ec-register-select'>";

    while ($sizeRow = $availableSizesResult->fetch_assoc()) {
        $sizeId = $sizeRow['size_id'];
        $sizeName = $sizeRow['size_name'];

        echo "<option value='$sizeId'>$sizeName</option>";
    }

    echo "</select>";
} else {
    // If no sizes are available, set size to "N/A"
echo "<span class='ec-rg-select-inner'>";
echo "<form action='add_to_cart.php' method='post'>";
echo "<input type='hidden' name='product_id' value='$productId'>";
    echo "<input type='hidden' name='selected_size' value='N/A'>";
}

echo "</span>";
echo "</span>";
echo "</div>";
echo "</div>";
echo "</div>";

// Display the quantity input and Add to Cart button
echo "<div class='ec-quickview-qty'>";
echo "<div class='qty-plus-minus'>";
echo "<input class='qty-input' type='text' name='ec_qtybtn' value='1' />";
echo "<input type='hidden' name='price' value='".$productPrice."'>";
echo "</div>";
echo "<div class='ec-quickview-cart'>";
echo "<button type='submit' class='btn btn-primary'><i class='fi-rr-shopping-basket'></i> Add To Cart</button>";
echo "</div>";
echo "</div>";
echo "</form>"; // Close the form
echo "</div>";
echo "</div>";
echo "</div>";

                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Ec Pagination Start -->
                    <div class="ec-pro-pagination">
                        <span>Showing <?php echo $offset + 1; ?>-<?php echo min($offset + $productsPerPage, $totalProducts); ?> of <?php echo $totalProducts; ?> item(s)</span>
                        <ul class="ec-pro-pagination-inner">
                            <?php
                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo "<li><a " . ($page == $i ? "class='active'" : "") . " href='?page=$i'>$i</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- Ec Pagination End -->
                    <!-- Shop content End -->
                </div>
               <!-- Sidebar Area Start -->
<div class="ec-shop-leftside col-lg-3 col-md-12 order-lg-first order-md-last">
    <form method="get" action="">
        <div id="shop_sidebar">
            <div class="ec-sidebar-heading">
                <h1>Filter Products By</h1>
            </div>
            <div class="ec-sidebar-wrap">
                <!-- Sidebar Category Block -->
                <div class="ec-sidebar-block">
                    <div class="ec-sb-title">
                        <h3 class="ec-sidebar-title">Category</h3>
                    </div>
                    <div class="ec-sb-block-content">
                        <ul>
                            <?php
                            // Fetch the first 7 categories from the 'category' table
                            $query = "SELECT * FROM category LIMIT 7";
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query failed: " . mysqli_error($conn));
                            }

                            // Check if there are any categories 
                            if (mysqli_num_rows($result) > 0) {
                                // Loop through the results and generate HTML
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $categoryId = $row['category_id'];
                                    $categoryName = $row['category_name'];
                            ?>
                                    <li>
                                        <div class="ec-sidebar-block-item">
                                            <input type="checkbox" name="category[]" value="<?php echo $categoryId; ?>" <?php echo in_array($categoryId, $selectedCategories) ? 'checked' : ''; ?> />
                                            <a href="#"><?php echo $categoryName; ?></a><span class="checked"></span>
                                        </div>
                                    </li>
                            <?php
                                }
                            } else {
                                echo "No categories found";
                            }
                            ?>
                        </ul>
                        <!-- Add more categories if needed -->
                        <li id="ec-more-toggle-content" style="padding: 0; display: none;">
                            <ul>
                                <?php
                                // Fetch category names starting from the 8th category
                                $query = "SELECT category_id, category_name FROM category LIMIT 7, 100"; // Assuming there are less than 100 categories

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
                                        <li>
                                            <div class="ec-sidebar-block-item">
                                                <input type="checkbox" name="category[]" value="<?php echo $categoryId; ?>" <?php echo in_array($categoryId, $selectedCategories) ? 'checked' : ''; ?> />
                                                <a href="#"><?php echo $categoryName; ?></a><span class="checked"></span>
                                            </div>
                                        </li>
                                <?php
                                    }
                                } else {
                                    // Handle the case where there are no categories
                                    echo "No categories found";
                                }
                                ?>
                            </ul>
                        </li>
                        <li>
                            <div class="ec-sidebar-block-item ec-more-toggle">
                                <span class="checked"></span><span id="ec-more-toggle">More Categories</span>
                            </div>
                        </li>
                    </div>
                </div>
                            <!-- Sidebar Brand Block -->
            <div class="ec-sidebar-block">
                <div class="ec-sb-title">
                    <h3 class="ec-sidebar-title">Brands</h3>
                </div>
                <div class="ec-sb-block-content">
                    <ul>
                        <?php
                        // Fetch brand names from the 'brands' table
                        $query = "SELECT brand_name, brand_id FROM brands";
                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }

                        // Check if there are any brands
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $brandName = $row['brand_name'];
                                $brandId = $row['brand_id'];

                        ?>
                                <li>
                                    <div class="ec-sidebar-block-item">
                                        <input type="checkbox" name="brand[]" value="<?php echo $brandId; ?>" <?php echo in_array($brandName, $selectedBrands) ? 'checked' : ''; ?> />
                                        <a href="#"><?php echo $brandName; ?></a><span class="checked"></span>
                                    </div>
                                </li>
                        <?php
                            }
                        } else {
                            // Handle the case where there are no brands
                            echo "No brands found";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- Sidebar Size Block -->
            <div class="ec-sidebar-block">
                <div class="ec-sb-title">
                    <h3 class="ec-sidebar-title">Size</h3>
                </div>
                <div class="ec-sb-block-content">
                    <ul>
                        <?php
                        // Fetch size names from the 'size' table
                        $query = "SELECT size_name, size_id FROM size LIMIT 10 OFFSET 1";
                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }

                        // Check if there are any sizes
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $sizeName = $row['size_name'];
                                $sizeID= $row['size_id'];
                        ?>
                                <li>
                                    <div class="ec-sidebar-block-item">
                                        <input type="checkbox" name="size[]" value="<?php echo $sizeID; ?>" <?php echo in_array($sizeName, $selectedSizes) ? 'checked' : ''; ?> />
                                        <a href="#"><?php echo $sizeName; ?></a><span class="checked"></span>
                                    </div>
                                </li>
                        <?php
                            }
                        } else {
                            // Handle the case where there are no sizes
                            echo "No sizes found";
                        }
                        ?>
                    </ul>
                </div>
            </div>

                <div class="ec-fs-pro-btn">
                    <a href="shop.php" class="btn btn-lg btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-lg btn-primary">Filter</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End side bar -->

            </div>
        </div>
    </section>
    <!-- End Shop page -->


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
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="quickview-pro-content">
                                <h5 class="ec-quick-title"><a href="product-left-sidebar.html">Handbag leather purse for women</a>
                                </h5>

                                <div class="ec-quickview-desc">Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                    since the 1500s,</div>
                                <div class="ec-quickview-price">
                                    <span class="new-price">$80.00</span>
                                </div>

                                <div class="ec-pro-variation">
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
    <script src="assets/js/plugins/nouislider.js"></script>

    <!-- Main Js -->
    <script src="assets/js/vendor/index.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>
<?php 
// Close the database connection
mysqli_close($conn);
?>