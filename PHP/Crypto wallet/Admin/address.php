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

// Fetch deposit address details
$query = "SELECT * FROM deposit_address";
$result = mysqli_query($con, $query);

if (!$result) {
    echo "Error: " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/address.css">
    <title>Deposit Address</title>
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
            <h1>Deposit Address</h1>
            <div class="address-table">
                <table border="1">
                    <tr>
                        <th>Coin</th>
                        <th>Address</th>
                        <th>Minimum Amount</th>
                        <th>Bonus</th>
                        <th>Withdrawal Fees</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <form method='post'>
                                <td><?php echo $row['coin']; ?></td>
                                <td><input type='text' name='address_<?php echo $row['coin']; ?>' value='<?php echo $row['address']; ?>'></td>
                                <td><input type='text' name='min_amount_<?php echo $row['coin']; ?>' value='<?php echo $row['min_amount']; ?>'></td>
                                <td><input type='text' name='bonus_<?php echo $row['coin']; ?>' value='<?php echo $row['bonus']; ?>'></td>
                                <td><input type='text' name='withdrawal_fees_<?php echo $row['coin']; ?>' value='<?php echo $row['withdrawal_fees']; ?>'></td>
                                <td><input type='submit' name='update_<?php echo $row['coin']; ?>' value='Update'></td>
                            </form>
                        </tr>
                    <?php
                        // Handle form submission for the current coin
                        if (isset($_POST['update_' . $row['coin']])) {
                            $coin = $row['coin'];
                            $address = $_POST['address_' . $coin];
                            $minAmount = $_POST['min_amount_' . $coin];
                            $bonus = $_POST['bonus_' . $coin];
                            $withdrawalFees = $_POST['withdrawal_fees_' . $coin];

                            // Update the database
                            $updateQuery = "UPDATE deposit_address SET address='$address', min_amount='$minAmount', bonus='$bonus', withdrawal_fees='$withdrawalFees' WHERE coin='$coin'";
                            $updateResult = mysqli_query($con, $updateQuery);

                            if (!$updateResult) {
                                echo "Error updating record: " . mysqli_error($con);
                            } else {
                                echo "<script>alert('Record updated successfully');</script>";
                                echo "<meta http-equiv='refresh' content='0'>";
                                // header('Location: address.php');
                                // exit;
                            }
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
