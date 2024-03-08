<?php
require_once '../db.php'; // Include your database connection file here
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">

    <!-- ekka CSS -->
    <link id="ekka-css" rel="stylesheet" href="assets/css/ekka.css" />

    <!-- FAVICON -->
    <link href="assets/img/favicon.png" rel="shortcut icon" />
</head>
<body>
    <div class="wrapper">
        <div class="ec-content-wrapper">
            <div class="content">
                <!-- Add Brand Modal -->
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <?php
                        // Check if category ID is provided in the URL
                        if (isset($_GET['brand_id'])) {
                            $brandId = $_GET['brand_id'];

                            // Retrieve category data for editing
                            $sql = "SELECT * FROM brands WHERE brand_id = '$brandId'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Fetch Brand data
                                $brandData = $result->fetch_assoc();

                                // Check if the form is submitted for updating brand
                                if(isset($_POST['update_brand'])) {
                                    $newBrandName = $_POST['brand_name'];

                                    // Update Brand name in the Brands table
                                    $updateQuery = "UPDATE brands SET brand_name = '$newBrandName' WHERE brand_id = '$brandId'";
                                    if ($conn->query($updateQuery) === TRUE) {
                                        // Redirect back to brand.php
                                        header("Location: brand.php");
                                        exit();
                                    } else {
                                        echo "Error updating brand: " . $conn->error;
                                    }
                                }
                                ?>
                                <form action="edit_brand.php?brand_id=<?php echo $brandId; ?>" method="post">
                                    <div class="modal-header px-4">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Brand</h5>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div class="row mb-2">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="brand_name">Brand Name</label>
                                                    <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo isset($brandData['brand_name']) ? $brandData['brand_name'] : ''; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer px-4">
                                        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-pill" name="update_brand">Update Brand</button>
                                    </div>
                                </form>
                                <?php
                            } else {
                                echo "Brand not found.";
                            }
                        } else {
                            echo "Brand ID not provided.";
                        }
                        ?>
                    </div>
                </div>
            </div> <!-- End Content -->
        </div> <!-- End Content Wrapper -->
    </div> <!-- End Page Wrapper -->
</body>
</html>
<?php
mysqli_close($conn);
?>
