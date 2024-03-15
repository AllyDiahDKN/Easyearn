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
// Check if user ID is provided in the URL
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Retrieve user data for editing
    $sql = "SELECT * FROM user WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $userData = $result->fetch_assoc();

        // Check if the form is submitted for updating user
        if(isset($_POST['update_user'])) {
            // Extract form data
            $newFirstName = $_POST['first_name'];
            $newLastName = $_POST['last_name'];
            $newUsername = $_POST['username'];
            //$newEmail = $_POST['email'];
            $newPassword = $_POST['password'];
            $newMobile = $_POST['mobile'];
            $newAddressId = $_POST['address_id'];

            // Update user data in the user table
            $updateQuery = "UPDATE user SET 
                            first_name = '$newFirstName', 
                            last_name = '$newLastName', 
                            username = '$newUsername',                                                          
                            password = '$newPassword', 
                            mobile = '$newMobile', 
                            address_id = '$newAddressId' 
                            WHERE user_id = '$userId'";

            if ($conn->query($updateQuery) === TRUE) {
                // Redirect back to user-list.php
                header("Location: user-list.php");
                exit();
            } else {
                echo "Error updating user: " . $conn->error;
            }
        }
    }

?> 
                           
                                <form action="edit_user.php?user_id=<?php echo $userId; ?>" method="post">
                                    <div class="modal-header px-4">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Update User</h5>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div class="row mb-2">
                                        <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="firstName">First name</label>
                                                    <input type="text" class="form-control" name="first_name" id="firstName" value="<?php echo isset($userData['first_name']) ? $userData['first_name'] : ''; ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="lastName">Last name</label>
                                                    <input type="text" class="form-control" name="last_name" id="lastName" value="<?php echo isset($userData['last_name']) ? $userData['last_name'] : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($userData['username']) ? $userData['username'] : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="mobile">Mobile</label>
                                                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo isset($userData['mobile']) ? $userData['mobile'] : ''; ?>" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" name="password" id="password" value="<?php echo isset($userData['password']) ? $userData['password'] : ''; ?>" required>
                                                </div>
                                            </div>
                                                 <!--  <div class="col-lg-6">
                                                            <div class="form-group mb-4">
                                                                <label for="activation">Activation</label>
                                                                <select class="form-select" name="activation" id="activation">
                                                                    <option value="0">Inactive</option>
                                                                    <option value="1">Active</option>
                                                                </select>
                                                            </div>
                                                        </div>-->

                                        <div class="col-lg-6">
                                            <div class="form-group mb-4">
                                                <label for="address_id">Address ID</label>
                                                <input type="text" class="form-control" name="address_id" id="address_id"value="<?php echo isset($userData['address_id']) ? $userData['address_id'] : ''; ?>" required>
                                            </div>
                                        </div>

                                    <!--   <div class="col-lg-6">
                                            <div class="form-group mb-4">
                                                <label for="commission">Commission</label>
                                                <input type="number" class="form-control" name="commission" id="commission" value="<?php echo isset($userData['commission']) ? $userData['commission'] : ''; ?>" required>
                                            </div>
                                        </div>-->
                                        </div>
                                    </div>
                                    <div class="modal-footer px-4">
                                        <a href="user-list.php" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</a>
                                        <button type="submit" class="btn btn-primary btn-pill" name="update_user">Update User</button>
                                    </div>
                                </form>
                                <?php
                            } else {
                                echo "User not found.";
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
