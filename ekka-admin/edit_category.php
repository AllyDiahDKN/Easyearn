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
                <!-- Update Category Modal -->
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <?php
                        // Check if category ID is provided in the URL
                        if (isset($_GET['category_id'])) {
                            $categoryId = $_GET['category_id'];

                            // Retrieve category data for editing
                            $sql = "SELECT * FROM category WHERE category_id = '$categoryId'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Fetch Category data
                                $categoryData = $result->fetch_assoc();

                                // Check if the form is submitted for updating category
                                if(isset($_POST['update_category'])) {
                                    $newCategoryName = $_POST['category_name'];

                                    // Update Category name in the Categories table
                                    $updateQuery = "UPDATE category SET category_name = '$newCategoryName' WHERE category_id = '$categoryId'";
                                    if ($conn->query($updateQuery) === TRUE) {
                                        // Redirect back to category.php
                                        header("Location: category.php");
                                        exit();
                                    } else {
                                        echo "Error updating category: " . $conn->error;
                                    }
                                }
                                ?>
                                <form action="edit_category.php?category_id=<?php echo $categoryId; ?>" method="post">
                                    <div class="modal-header px-4">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Category</h5>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div class="row mb-2">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="category_name">Category Name</label>
                                                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer px-4">
                                        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-pill" name="update_category">Update Category</button>
                                    </div>
                                </form>
                                <?php
                            } else {
                                echo "Category not found.";
                            }
                        } else {
                            echo "Category ID not provided.";
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
