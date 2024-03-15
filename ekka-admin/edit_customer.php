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
                    // Check if customer_id is set and numeric
                    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                        $customerId = $_GET['id'];
                    
                        // Retrieve customer data for editing
                        $sql = "SELECT * FROM customers WHERE id = '$customerId'";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            // Fetch customer data
                            $customerData = $result->fetch_assoc();
                    
                            // Check if the form is submitted for updating customer
                            if(isset($_POST['update_customer'])) {
                                // Retrieve updated form data
                                $newFirstName = $_POST['first_name'];
                                $newLastName = $_POST['last_name'];
                                $newEmail = $_POST['email'];
                                $newCity = $_POST['city'];
                                $newCountry = $_POST['country'];
                                $newMobile = $_POST['mobile'];
                                $newAddress = $_POST['address'];
                                $newUserId = $_POST['user_id'];
                                $newHouseNumber = $_POST['house_number'];
                    
                                // Update customer data in the customers table
                                $updateQuery = "UPDATE customers SET 
                                                first_name = '$newFirstName', 
                                                last_name = '$newLastName', 
                                                email = '$newEmail', 
                                                city = '$newCity', 
                                                country = '$newCountry', 
                                                mobile = '$newMobile', 
                                                address = '$newAddress', 
                                                user_id = '$newUserId', 
                                                house_number = '$newHouseNumber' 
                                                WHERE id = '$customerId'";
                    
                                if ($conn->query($updateQuery) === TRUE) {
                                    // Redirect back to customer-list.php or wherever you want
                                    header("Location: customer-list.php");
                                    exit();
                                } else {
                                    echo "Error updating customer: " . $conn->error;
                                }
                            }
                    ?>

<form method="post" action="">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Customer</h5>
    </div>

    <div class="modal-body px-4">
        <div class="row mb-2">
            <!-- First Name -->
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" name="first_name" id="firstName" value="<?php echo isset($customerData['first_name']) ? $customerData['first_name'] : ''; ?>" placeholder="Enter First Name">
                </div>
            </div>

            <!-- Last Name -->
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" name="last_name" id="lastName" value="<?php echo isset($customerData['last_name']) ? $customerData['last_name'] : ''; ?>" placeholder="Enter Last Name">
                </div>
            </div>

            <!-- Email -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($customerData['email']) ? $customerData['email'] : ''; ?>" placeholder="Enter Email">
                </div>
            </div>

            <!-- City -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city" value="<?php echo isset($customerData['city']) ? $customerData['city'] : ''; ?>" placeholder="Enter City">
                </div>
            </div>

            <!-- Country -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" name="country" id="country" value="<?php echo isset($customerData['country']) ? $customerData['country'] : ''; ?>" placeholder="Enter Country">
                </div>
            </div>

            <!-- Mobile -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo isset($customerData['mobile']) ? $customerData['mobile'] : ''; ?>" placeholder="Enter Mobile Number">
                </div>
            </div>

            <!-- Address -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo isset($customerData['address']) ? $customerData['address'] : ''; ?>" placeholder="Enter Address">
                </div>
            </div>

            <!-- User -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="userId">User</label>
                    <select name="user_id" id="userId" class="form-control">
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
                                $selected = ($userId == $customerData['user_id']) ? "selected" : "";
                                echo "<option value=\"$userId\" $selected>$userId - $firstName $lastName</option>";
                            }
                        } else {
                            echo "<option value=\"\">No users found</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- House Number -->
            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="houseNumber">House Number</label>
                    <input type="text" class="form-control" name="house_number" id="houseNumber" value="<?php echo isset($customerData['house_number']) ? $customerData['house_number'] : ''; ?>" placeholder="Enter House Number">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer px-4">
        <a href="customer-list.php" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-primary btn-pill" name="update_customer">Update Changes</button>
    </div>
</form>
<?php
                        } else {
                            echo "No customer found with the provided ID.";
                        }
                    } else {
                        echo "Invalid customer ID.";
                    }
                    ?>
        

                    </div>
                </div>
            </div> <!-- End Content -->
        </div> <!-- End Content Wrapper -->
    </div> <!-- End Page Wrapper -->
</body>
</html>
<?php mysqli_close($conn); ?>