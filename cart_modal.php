<?php
// Include your database connection file
include('db.php');
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

// Fetch user's cart items
$userId = $_SESSION['user_id']; // Assuming you have a session variable for user_id
}
else {
    $userId=0;
}
$cartQuery = "SELECT c.cart_id, p.product_name, p.product_image, c.size_id, c.quantity, p.price
              FROM cart c
              JOIN products p ON c.product_id = p.product_id
              WHERE c.user_id = $userId AND status = 'In Cart'";

$cartResult = $conn->query($cartQuery);

// Calculate total
$total = 0;
?>

<div class="ec-side-cart-overlay"></div>
<div id="ec-side-cart" class="ec-side-cart">
    <div class="ec-cart-inner">
        <div class="ec-cart-top">
            <div class="ec-cart-title">
                <span class="cart_title">My Cart</span>
                <button class="ec-close">×</button>
            </div>
            <ul class="eccart-pro-items">
                <?php
                while ($cartItem = $cartResult->fetch_assoc()) {
                    $cartId = $cartItem['cart_id'];
                    $productName = $cartItem['product_name'];
                    $productImage = $cartItem['product_image'];
                    $sizeId = $cartItem['size_id'];
                    $quantity = $cartItem['quantity'];
                    $price = $cartItem['price'];
                    $subtotal = $quantity * $price;

                    // Display each cart item
                    echo "<li>";
                    echo "<a href='product-left-sidebar.html' class='sidekka_pro_img'><img src='assets/images/product-image/$productImage' alt='$productName'></a>";
                    echo "<div class='ec-pro-content'>";
                    echo "<a href='' class='cart_pro_title'>$productName</a>";

                    // Display size if it's not 0
                    if ($sizeId != 0) {
                        echo "<span class='cart-price'><span>Size:</span> $sizeId</span>";
                    }

                    echo "<span class='cart-price'><span>Price:</span> $price x $quantity</span>";
                    echo "<a href='delete_from_cart.php?cart_id=$cartId' class='remove'>×</a>";
                    echo "</div>";
                    echo "</li>";

                    // Update total
                    $total += $subtotal;
                }
                ?>
            </ul>
        </div>
        <div class="ec-cart-bottom">
            <div class="cart-sub-total">
                <table class="table cart-table">
                    <tbody>
                        <tr>
                            <td class="text-left">Total :</td>
                            <td class="text-right"><?php echo 'Tsh' . number_format($total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="cart_btn">
                <a href="checkout.php" class="btn btn-primary">Checkout</a>
            </div>
        </div>
    </div>
</div>
