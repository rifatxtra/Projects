<?php
include('../admin/config.php');
if ($con->connect_error) {
    header("Location: error/error.php");
    exit;
}
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    // User is not logged in, redirect to login page
    echo '<script type="text/javascript">';
    echo 'alert("Please Log in First")';
    echo '</script>';
    header("Location: login.php");
    exit;
}

// Retrieve user ID from session
$userId = $_SESSION['admin'];
// get name
$query = "SELECT User_ID, User_Name FROM users";
$result = mysqli_query($con, $query);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['User_ID'] == $userId) $name = $row['User_Name'];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <div class="menu">
            <form method="post">
                <input type="submit" name="profile" value="Profile">
                <input type="submit" name="users" value="Users">
                <input type="submit" name="pending" value="Pending Transaction">
                <input type="submit" name="transaction" value="Transactions">
                <input type="submit" name="address" value="Address">
                <!-- <input type="submit" name="fees_bonus" value="Fees & Bonus"> -->
                <!-- <input type="submit" name="price" value="Prices"> -->
                <input type="submit" name="signout" value="Sign Out">
            </form>
            <?php
            if (isset($_POST['signout'])) {
                $_SESSION = array();
                // Destroy the session
                session_destroy();
                header("Location: ../login/index.php");
                exit;
            } else if (isset($_POST['profile'])) {
                header("Location: profile.php");
                exit;
            } else if (isset($_POST['users'])) {
                header("Location: users.php");
                exit;
            } else if (isset($_POST['pending'])) {
                header("Location: pending.php");
                exit;
            } else if (isset($_POST['transaction'])) {
                header("Location: transactions.php");
                exit;
            } else if (isset($_POST['address'])) {
                header("Location: address.php");
                exit;
            }
            ?>
        </div>
        <div class="content">
            <div class="profile">
                <h2>Profile</h2>
                <?php
                // Fetch user data based on $userId
                $query = "SELECT * FROM users WHERE User_ID = '$userId'";
                $result = mysqli_query($con, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $first_name = $row['First_Name'];
                    $last_name = $row['Last_Name'];
                    $username = $row['User_Name'];
                    $phone_no = $row['Phone_No'];
                    $password_hash = $row['Password'];
                    $email = $row['Email'];
                } else {
                    // Handle error, such as user not found
                    die("User not found.");
                }
                // Check if form is submitted for updating information
                if (isset($_POST['changeaccinfo'])) {
                    $new_username = $_POST['username'];
                    $new_phone_no = $_POST['phone_no'];
                    $new_email = $_POST['mail'];
                    // Validate phone number format
                    if (!preg_match('/^0\d{10}$/', $new_phone_no)) {
                        echo '<script>alert("Phone number must start with 0 and have 11 digits.")</script>';
                    } else {
                        // Additional logic for updating user information
                        // Make sure to handle any errors or success messages accordingly
                    }
                }
                ?>
                <div class="container_profile">
                    <div class="profile_half">
                        <h1>Account Details</h1>
                        <form method="post">
                            <div class="name">
                                <div class="first">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" id="first_name" name="first_name" value="<?php echo $first_name ?>" required>
                                </div>
                                <div>
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" id="last_name" name="last_name" value="<?php echo $last_name ?>" required>
                                </div>
                            </div>
                            <div class="user_phone">
                                <div class="first">
                                    <label for="username">Username:</label>
                                    <input type="text" id="username" name="username" value="<?php echo $username ?>" required>
                                </div>
                                <div>
                                    <label for="phone_no">Phone Number:</label>
                                    <input type="text" id="phone_no" name="phone_no" value="<?php echo $phone_no ?>" required>
                                </div>
                            </div>
                            <div class="pass_mail">
                                <div class="first">
                                    <label for="mail">Email:</label>
                                    <input type="text" id="mail" name="mail" value="<?php echo $email ?>" required>
                                </div>
                                <div>
                                    <label for="password">Enter Password To Confirm It's You:</label>
                                    <input type="password" id="password" name="password" required>
                                </div>
                            </div>
                            <div>
                                <input type="submit" name="changeaccinfo" value="Save">
                            </div>
                        </form>
                    </div>
                    <div class="profile_last">
                        <h1>Change your password</h1>
                        <form method="post">
                            <div>
                                <label for="oldpass">Old Password:</label>
                                <input type="password" id="oldpass" name="oldpass" required>
                            </div>
                            <div>
                                <label for="newpass">New Password:</label>
                                <input type="password" id="newpass" name="newpass" required>
                            </div>
                            <div>
                                <label for="confirmpassword">Confirm New Password:</label>
                                <input type="password" id="confirmpassword" name="confirmpassword" required>
                            </div>
                            <div>
                                <input type="submit" name="changepass" value="Change Password">
                            </div>
                        </form>
                        <?php
                        // Check if form is submitted for changing the password
                        if (isset($_POST['changepass'])) {
                            // Retrieve input values from the form
                            $old_password = $_POST['oldpass'];
                            $new_password = $_POST['newpass'];
                            $confirm_password = $_POST['confirmpassword'];
                            
                            // Your password validation and update logic here
                            // Make sure to handle any errors or success messages accordingly
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>