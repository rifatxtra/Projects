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
$trckr = 0;
$coin_bal;
$id;
$coin = '';
$uid;
$amount;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pending.css">
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
            <?php
            echo "<div class='transactions'>";
            echo "<h1>Pending Transactions</h1>";
            echo "<div class='search-form'>";
            echo "<form method='post'>";
            echo "<label for='search_user'>Search User:</label>";
            echo "<input type='text' id='search_user' name='search_user' placeholder='Enter username'>";
            echo "<label for='transaction_type'>Transaction Type:</label>";
            echo "<select id='transaction_type' name='transaction_type'>";
            echo "<option value='deposit'>Deposit</option>";
            echo "<option value='withdrawal'>Withdrawal</option>";
            echo "</select>";
            echo "<button type='submit' name='search'>Search</button>";
            echo "</form>";
            echo "</div>";
            echo "<br>";
            echo "<div class='transaction-table'>";
            echo "<table border='1'>";

            // Fetch pending deposit or withdrawal transactions based on selection
            if (isset($_POST['search'])) {
                $search_user = $_POST['search_user'];
                $transaction_type = $_POST['transaction_type'];
                $query = "SELECT User_ID FROM users WHERE User_Name = '$search_user'";
                $result = mysqli_query($con, $query);

                if ($result) {
                    // Check if any rows were returned
                    if (mysqli_num_rows($result) > 0) {
                        // Fetch the User_ID from the first row
                        $row = mysqli_fetch_assoc($result);
                        $user_id = $row['User_ID'];
                        // Use $user_id as needed
                    } else {
                        // No user found with the given username
                        echo "User not found";
                    }
                } else {
                    // Error executing the query
                    echo "Error: " . mysqli_error($con);
                }
                if ($search_user != '') {
                    if ($transaction_type == 'deposit') {
                        $trckr = 1;
                        $query = "SELECT * FROM deposit_history WHERE uid=$user_id  AND stats = 'pending'";
                    } elseif ($transaction_type == 'withdrawal') {
                        $trckr = 2;
                        $query = "SELECT * FROM withdrawal_history WHERE uid=$user_id AND withdrawal_status = 'pending'";
                    }
                } else {
                    if ($transaction_type == 'deposit') {
                        $trckr = 1;
                        $query = "SELECT * FROM deposit_history WHERE stats = 'pending'";
                    } elseif ($transaction_type == 'withdrawal') {
                        $trckr = 2;
                        $query = "SELECT * FROM withdrawal_history WHERE withdrawal_status = 'pending'";
                    }
                }
            }

            // Fetch and display pending transactions
            $result = mysqli_query($con, $query);
            if ($result && $trckr == 1) {
                echo "<tr>";
                echo "<th>Transaction No</th>";
                echo "<th>Coin</th>";
                echo "<th>User ID</th>";
                echo "<th>Transaction ID</th>";
                echo "<th>Amount</th>";
                echo "<th>Status</th>";
                echo "<th>Bonus</th>";
                echo "<th>Date</th>";
                echo "<th>Action</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['coin']}</td>";
                    $coin = $row['coin'];
                    echo "<td>{$row['uid']}</td>";
                    echo "<td>{$row['txnid']}</td>";
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['stats']}</td>";
                    echo "<td>{$row['bonus']}</td>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='id' value='{$row['id']}'>";
                    echo "<input type='hidden' name='coin' value='{$row['coin']}'>";
                    echo "<input type='hidden' name='uid' value='{$row['uid']}'>";
                    echo "<input type='hidden' name='amount' value='{$row['amount']}'>";
                    echo "<input type='submit' name='reject_deposit' value='Reject'>";
                    echo "<input type='submit' name='approve_deposit' value='Approve'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else if ($result && $trckr == 2) {
                echo "<tr>";
                echo "<th>Withdrawal ID</th>";
                echo "<th>Coin</th>";
                echo "<th>Amount</th>";
                echo "<th>Address</th>";
                echo "<th>Status</th>";
                echo "<th>User ID</th>";
                echo "<th>Withdrawal Date</th>";
                echo "<th>Fees</th>";
                echo "<th>Action</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['withdrawal_id']}</td>";
                    echo "<td>{$row['coin']}</td>";
                    $coin = $row['coin'];
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>{$row['withdrawal_status']}</td>";
                    echo "<td>{$row['uid']}</td>";
                    echo "<td>{$row['withdrawal_date']}</td>";
                    echo "<td>{$row['fees']}</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='withdrawal_id' value='{$row['withdrawal_id']}'>";
                    echo "<input type='submit' name='reject_withdrawal' value='Reject'>";
                    echo "<input type='submit' name='approve_withdrawal' value='Approve'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }

            // Handle deposit rejection
            if (isset($_POST['reject_deposit'])) {
                $id = $_POST['id'];
                $query = "UPDATE deposit_history SET stats = 'rejected' WHERE id = '$id'";
                mysqli_query($con, $query);
            }

            // Handle deposit approval
            if (isset($_POST['approve_deposit'])) {
                // Ensure $coin and $user_id are defined and initialized
                $coin = $_POST['coin']; // Fetch coin from form submission
                $user_id = $_POST['uid']; // Fetch uid from form submission

                // Fetch current balance and bonus from balance and deposit_history tables
                $query_balance = "SELECT `$coin` FROM balance WHERE u_id = '$user_id'";
                $query_bonus = "SELECT bonus FROM deposit_history WHERE id = '{$_POST['id']}'";
                $result_balance = mysqli_query($con, $query_balance);
                $result_bonus = mysqli_query($con, $query_bonus);

                if (!$result_balance || !$result_bonus) {
                    echo "Error fetching balance or bonus: " . mysqli_error($con);
                } else {
                    $row_balance = mysqli_fetch_assoc($result_balance);
                    $row_bonus = mysqli_fetch_assoc($result_bonus);
                    if ($row_balance && $row_bonus) {
                        // Use correct column name from the balance table
                        $current_balance = $row_balance[$coin];
                        $bonus = $row_bonus['bonus'];

                        // Fetch amount from form submission
                        $deposited_amount = $_POST['amount'];

                        // Update balance
                        $new_balance = $current_balance + $deposited_amount + $bonus;
                        $query_update_balance = "UPDATE balance SET `$coin` = '$new_balance' WHERE u_id = '$user_id'";
                        $result_update_balance = mysqli_query($con, $query_update_balance);

                        if (!$result_update_balance) {
                            echo "Error updating balance: " . mysqli_error($con);
                        } else {
                            // Update deposit history status
                            $id = $_POST['id'];
                            $query_update_status = "UPDATE deposit_history SET stats = 'approved' WHERE id = '$id'";
                            $result_update_status = mysqli_query($con, $query_update_status);

                            if (!$result_update_status) {
                                echo "Error updating deposit status: " . mysqli_error($con);
                            } else {
                                echo "Balance updated successfully";
                            }
                        }
                    } else {
                        echo "No balance or bonus found for user";
                    }
                }
            }
            // Handle withdrawal rejection
            if (isset($_POST['reject_withdrawal'])) {
                $withdrawal_id = $_POST['withdrawal_id'];

                // Fetch withdrawal details
                $withdrawal_query = "SELECT * FROM withdrawal_history WHERE withdrawal_id = '$withdrawal_id'";
                $withdrawal_result = mysqli_query($con, $withdrawal_query);

                if ($withdrawal_result && mysqli_num_rows($withdrawal_result) > 0) {
                    $withdrawal_row = mysqli_fetch_assoc($withdrawal_result);
                    $withdrawal_coin = $withdrawal_row['coin'];
                    $withdrawal_amount = $withdrawal_row['amount'];
                    $withdrawal_fees = $withdrawal_row['fees'];
                    $user_id = $withdrawal_row['uid'];

                    // Return amount + fees to balance
                    $return_amount = $withdrawal_amount + $withdrawal_fees;
                    $return_query = "UPDATE balance SET `$withdrawal_coin` = `$withdrawal_coin` + $return_amount WHERE u_id = '$user_id'";
                    $return_result = mysqli_query($con, $return_query);

                    if ($return_result) {
                        // Update withdrawal status to rejected
                        $update_query = "UPDATE withdrawal_history SET withdrawal_status = 'rejected' WHERE withdrawal_id = '$withdrawal_id'";
                        $update_result = mysqli_query($con, $update_query);

                        if ($update_result) {
                            echo "Withdrawal rejected successfully";
                        } else {
                            echo "Error updating withdrawal status: " . mysqli_error($con);
                        }
                    } else {
                        echo "Error returning amount to balance: " . mysqli_error($con);
                    }
                } else {
                    echo "Withdrawal details not found";
                }
            }
            // Handle withdrawal approval
            if (isset($_POST['approve_withdrawal'])) {
                $withdrawal_id = $_POST['withdrawal_id'];
                // Perform approval logic here
                $query = "UPDATE withdrawal_history SET withdrawal_status = 'approved' WHERE withdrawal_id = '$withdrawal_id'";
                $result = mysqli_query($con, $query);

                if (!$result) {
                    echo "Error updating withdrawal status: " . mysqli_error($con);
                } else {
                    echo "Withdrawal approved successfully";
                }
            }
            echo "</table>";
            echo "</div>";
            echo "</div>";
            ?>
        </div>
    </div>
</body>

</html>