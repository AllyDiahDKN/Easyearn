<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        /* Add custom styles for receipt */
        /* You can style the receipt layout here */
    </style>
</head>
<body>
    <h1>Order Receipt</h1>
    <h2>User Information</h2>
    <p><strong>Name:</strong> <?php echo $orderDetails['user_first_name']; ?></p>
    <p><strong>Mobile:</strong> <?php echo $orderDetails['user_mobile']; ?></p>
    <h2>Customer Information</h2>
    <p><strong>Name:</strong> <?php echo $orderDetails['customer_first_name']; ?></p>
    <p><strong>Mobile:</strong> <?php echo $orderDetails['customer_mobile']; ?></p>
    <h2>Order Details</h2>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
            </tr>
            
        </thead>
        <tbody>
 <tr>
                <td><?php echo $orderDetails['product_name']; ?></td>
                <td><?php echo $orderDetails['quantity']; ?></td>
            </tr>
            <tr>
                <th>Discount: <?php echo $orderDetails['discount']; ?></th>
                <th>Total: <?php echo $orderDetails['total']; ?></th>
            </tr>
        </tbody>
    </table>
</body>
</html>
