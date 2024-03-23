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
                <div class="modal-dialog modal-dialog-centered modal-lg" id="addUser" role="document">
                    <div class="modal-content">
                    <?php
if (isset($_GET['id'])) {
    $adminId = $_GET['id'];
    
    // Retrieve admin data for editing
    $sql = "SELECT * FROM admin WHERE id = '$adminId'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Fetch admin data
        $adminData = $result->fetch_assoc();
        
        // Check if the form is submitted for updating admin
        if(isset($_POST['update_admin'])) {
            $newUsername = $_POST['username'];
            $newEmail = $_POST['email'];
            $newPassword = $_POST['password'];
            $newFirstName = $_POST['firstname'];
            $newLastName = $_POST['lastname'];
            $newMobile = $_POST['mobile'];
            $newPermissionType = $_POST['permission_type'];
    
            // Update admin data in the admin table
            $updateQuery = "UPDATE admin SET username = '$newUsername', email = '$newEmail', password = '$newPassword', first_name = '$newFirstName', last_name = '$newLastName', mobile = '$newMobile', permission_type = '$newPermissionType' WHERE id = '$adminId'";
            if ($conn->query($updateQuery) === TRUE) {
                // Redirect back to admin-list.php or wherever you want
                header("Location: admin-list.php");
                exit();
            } else {
                echo "Error updating admin: " . $conn->error;
            }
        }
?>

<form action="" method="post">
    <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Admin</h5>
    </div>

    <div class="modal-body px-4">
        <div class="row mb-2">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $adminData['username']; ?>" placeholder="Username">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $adminData['first_name']; ?>" placeholder="First Name">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $adminData['last_name']; ?>" placeholder="Last Name">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $adminData['email']; ?>" placeholder="Email">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo $adminData['password']; ?>" placeholder="Password">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $adminData['mobile']; ?>" placeholder="Mobile">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group mb-4">
                    <label for="permission_type">Permission Type</label>
                    <select class="form-control" id="permission_type" name="permission_type">
                        <option value="<?php echo $adminData['permission_type']; ?>" selected><?php echo $adminData['permission_type']; ?></option>
                        <option value="Admin">Admin</option>
                        <option value="Operator">Operator</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer px-4">
        <a href="admin-list.php" class="btn btn-secondary btn-pill" data-bs-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-primary btn-pill" name="update_admin">Update Admin</button>
    </div>
</form>

                                <?php
                             } else {
                                echo "Admin not found.";
                            }
                        } else {
                            echo "Admin ID not provided.";
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
