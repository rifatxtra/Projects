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
    <link rel="stylesheet" href="../css/users.css">
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
            <div class="users">
                <h1>User Management</h1>
                <div class="search-form">
                    <form method="post">
                        <label for="search_user">Search User:</label>
                        <input type="text" id="search_user" name="search_user" placeholder="Enter username">
                        <button type="submit" name="search">Search</button>
                    </form>
                </div>
                <br>
                <div class="user-table">
                    <table border="1">
                        <tr>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Ban Status</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        include('config.php');

                        // Fetch all users or targeted user if username is provided
                        if (isset($_POST['search'])) {
                            $search_user = $_POST['search_user'];
                            if (!empty($search_user)) {
                                $query = "SELECT * FROM users WHERE User_Name = '$search_user'";
                            } else {
                                $query = "SELECT * FROM users";
                            }
                        } else {
                            $query = "SELECT * FROM users";
                        }

                        $result = mysqli_query($con, $query);

                        if ($result) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['User_ID']}</td>";
                                    echo "<td>{$row['First_Name']}</td>";
                                    echo "<td>{$row['Last_Name']}</td>";
                                    echo "<td>{$row['User_Name']}</td>";
                                    echo "<td>{$row['Phone_No']}</td>";
                                    echo "<td>{$row['Email']}</td>";
                                    echo "<td>";
                                    if ($row['ban_status'] == 'ban') {
                                        echo "<span class='ban-status'>Banned</span>";
                                    } else {
                                        echo "<span class='unban-status'>Unbanned</span>";
                                    }
                                    echo "</td>";
                                    echo "<td>";
                                    if ($row['ban_status'] == 'ban') {
                                        echo "<form method='post'>";
                                        echo "<input type='hidden' name='user_id' value='{$row['User_ID']}'>";
                                        echo "<button class='unban-btn' type='submit' name='unban'>Unban</button>";
                                        echo "</form>";
                                    } else {
                                        echo "<form method='post'>";
                                        echo "<input type='hidden' name='user_id' value='{$row['User_ID']}'>";
                                        echo "<button class='ban-btn' type='submit' name='ban'>Ban</button>";
                                        echo "</form>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No users found.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Error: " . mysqli_error($con) . "</td></tr>";
                        }

                        // Ban or unban user logic
                        if (isset($_POST['ban'])) {
                            $user_id = $_POST['user_id'];
                            $update_query = "UPDATE users SET ban_status = 'ban' WHERE User_ID = $user_id";
                            mysqli_query($con, $update_query);
                            echo "<meta http-equiv='refresh' content='0'>";
                        } elseif (isset($_POST['unban'])) {
                            $user_id = $_POST['user_id'];
                            $update_query = "UPDATE users SET ban_status = 'unban' WHERE User_ID = $user_id";
                            mysqli_query($con, $update_query);
                            echo "<meta http-equiv='refresh' content='0'>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>

</html>