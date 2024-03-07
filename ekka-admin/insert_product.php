<?php
require_once '../db.php'; // Adjust the path as needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productName = $_POST['product_name'];
    $categoryId = $_POST['category_id'];
    $brandId = $_POST['brand_id'];
    $availability = $_POST['availability'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $code = $_POST['code'];
    $sizes = $_POST['sizes'];

    // Image upload
    $targetDir = "../assets/images/product-image/"; // Directory where uploaded images will be saved
    $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
    $fileName= basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["product_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["product_image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert product details into products table
    $sql = "INSERT INTO products (product_name, category_id, brand_id, availability, price, stock, description, code, product_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiidiiss", $productName, $categoryId, $brandId, $availability, $price, $quantity, $description, $code, $fileName);
    $stmt->execute();
    $productId = $stmt->insert_id;

    // Insert selected sizes into product_sizes table
    foreach ($sizes as $sizeId) {
        $sql = "INSERT INTO product_size (product_id, size_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $productId, $sizeId);
        $stmt->execute();
    }

    // Redirect back to the product page or show a success message
    header("Location: product-edit.php?product_id=$productId");
} else {
    // Redirect to the error page or display an error message
    echo "Invalid request method";
}

$conn->close();
?>
