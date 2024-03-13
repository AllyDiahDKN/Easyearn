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

    // Retrieve customer data from the database
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if customer exists
    if ($result->num_rows > 0) {
        $customerData = $result->fetch_assoc();

        if(isset($_POST['update_customer'])) {
            // Extract data from the form submission
            $newFirstName = $_POST['first_name'];
            $newLastName = $_POST['last_name'];
            $newEmail = $_POST['email'];
            $newCity = $_POST['city'];
            $newCountry = $_POST['country'];
            $newMobile = $_POST['mobile'];
            $newAddress = $_POST['address'];
            $newUserId = $_POST['user_id']; // Assuming this is the foreign key for user
            $newHouseNumber = $_POST['house_number'];
        
            // Update customer data in the database
            $stmt = $conn->prepare("UPDATE customers SET first_name = ?, last_name = ?, email = ?, city = ?, country = ?, mobile = ?, address = ?, user_id = ?, house_number = ? WHERE id = ?");
            $stmt->bind_param("sssssssssi", $newFirstName, $newLastName, $newEmail, $newCity, $newCountry, $newMobile, $newAddress, $newUserId, $newHouseNumber, $customerId);
        
            if ($stmt->execute()) {
                // If successful, redirect back to the previous page
                header("Location: customer-list.php");
                exit();
            } else {
                echo "Error updating customer: " . $conn->error;
            }
        }
?>
 <form method="post" action="edit_customer.php" >
            <div class="modal-header px-4">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Customer</h5>
            </div>

            <div class="modal-body px-4">
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="firstName" value="<?php echo isset($customerData['first_name']) ? $customerData['first_name'] : ''; ?>" placeholder="Enter First Name">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="lastName" value="<?php echo isset($customerData['last_name']) ? $customerData['last_name'] : ''; ?>" placeholder="Enter Last Name">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($customerData['email']) ? $customerData['email'] : ''; ?>" placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" id="city" value="<?php echo isset($customerData['city']) ? $customerData['city'] : ''; ?>" placeholder="Enter City">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="country">Country</label>
                            <select name="country" id="country" class="form-control">
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
                            <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo isset($customerData['mobile']) ? $customerData['mobile'] : ''; ?>" placeholder="Enter Mobile Number">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" name="address" id="address" value="<?php echo isset($customerData['address']) ? $customerData['address'] : ''; ?>" placeholder="Enter Address">
                        </div>
                    </div>

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
                            <input type="text" class="form-control" name="house_number" id="houseNumber" value="<?php echo isset($customerData['house_number']) ? $customerData['house_number'] : ''; ?>" placeholder="Enter House Number">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer px-4">
                <a href="customer-list.php" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</a>
                <button type="submit" class="btn btn-primary btn-pill">Update Changes</button>
            </div>
        </form>
        <?php
    } else {
        echo "Customer not found.";
    }
} else {
    echo "Customer ID not provided.";
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