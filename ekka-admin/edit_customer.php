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
                      
                    // Check if customer ID is provided in the URL
                    if (isset($_GET['id'])) {
                        $customerId = $_GET['id'];

                        // Prepare and bind parameters
                        $stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
                        $stmt->bind_param("i", $customerId);

                        // Execute the query
                        $stmt->execute();

                        // Get the result
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Fetch customer data
                            $customerData = $result->fetch_assoc();

                            // Update customer data in the customer table
                            $stmt = $conn->prepare("UPDATE customer SET first_name = ?, last_name = ?, email = ?, city = ?, country = ?, mobile = ?, address = ?, user_id = ?, house_number = ? WHERE id = ?");
                            $stmt->bind_param("sssssssssi", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['city'], $_POST['country'], $_POST['mobile'], $_POST['address'], $_POST['user_id'], $_POST['house_number'], $customerId);

                            if ($stmt->execute()) {
                                // If successful, redirect back to the previous page
                                header("Location: {$_SERVER['HTTP_REFERER']}");
                                exit();
                            } else {
                                echo "Error updating customer: " . $conn->error;
                            }
                        } else {
                            echo "Customer not found.";
                        }
                                ?>
                          	<form method="post" action="edit_customer.php">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Seller</h5>
    </div>

    <div class="modal-body px-4">
        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" placeholder="Enter First Name">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" placeholder="Enter Email">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city"value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" placeholder="Enter City">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="country">Country</label>
                 
                                        <select name="country" id="country"
                                            class="form-control">
                                            <option selected disabled>Country *</option>
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
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>"placeholder="Enter Mobile Number">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" placeholder="Enter Address">
                </div>
            </div>

            <div class="col-lg-6">
			<div class="form-group mb-4">
    <label for="userId">User</label>
    <select name="userId" id="userId" class="form-control" required>
        <option selected disabled>Select User *</option>
        <?php
        // Fetch user information from the database
        $sql = "SELECT user_id, first_name, last_name FROM user";
        $result = $conn->query($sql);

        // Check if there are users
        if ($result->num_rows > 0) {
            // Output the options
            while ($row = $result->fetch_assoc()) {
                $userId = $row['user_id'];
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                echo "<option value=\"$userId\">$userId - $firstName $lastName</option>";
            }
        } else {
            echo "<option value=\"\">No users found</option>";
        }
        ?>
    </select>
</div>

            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="houseNumber">House Number</label>
                    <input type="text" class="form-control" name="houseNumber" id="houseNumber" value="<?php echo isset($categoryData['category_name']) ? $categoryData['category_name'] : ''; ?>" placeholder="Enter House Number">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer px-4">
        <button type="button" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-pill">Save Contact</button>
    </div>
</form>
                                <?php
                            } else {
                                echo "Category not found.";
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
