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

<?php
$txn = 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Transactions</title>
    <link rel="stylesheet" href="../css/transaction.css">
</head>

<body>
    <div class="menu">
        <form method="post">
            <input type="submit" name="profile" value="Profile">
            <input type="submit" name="users" value="Users">
            <input type="submit" name="pending" value="Pending Transaction">
            <input type="submit" name="transaction" value="Transactions">
            <input type="submit" name="address" value="Address">
            <input type="submit" name="signout" value="Sign Out">
        </form>
    </div>

    <div class="container">
        <h1>User Transactions</h1>
        <div class="search-form">
            <form method="post">
                <label for="search_user">Search User:</label>
                <input type="text" id="search_user" name="search_user" placeholder="Enter username">
                <label for="transaction_type">Transaction Type:</label>
                <select id="transaction_type" name="transaction_type">
                    <option value="send">Send</option>
                    <option value="receive">Receive</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdraw">Withdraw</option>
                    <option value="convert">Convert</option>
                </select>
                <button type="submit" name="search">Search</button>
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
        <br>
        <div class="transaction-table">
            <table border="1" style="<?php echo isset($_POST['search']) ? '' : 'display:none;' ?>">
                <?php
                include('config.php');
                if (isset($_POST['search'])) {
                    $transaction_type = $_POST['transaction_type'];
                    // Check if a username is provided
                    if (!empty($_POST['search_user'])) {
                        $search_user = $_POST['search_user'];
                        // Fetch the user's ID based on the provided username
                        $user_query = "SELECT User_ID FROM users WHERE User_Name = '$search_user'";
                        $user_result = mysqli_query($con, $user_query);

                        if ($user_result && mysqli_num_rows($user_result) > 0) {
                            $user_row = mysqli_fetch_assoc($user_result);
                            $user_id = $user_row['User_ID'];

                            // Construct the query based on transaction type
                            if ($transaction_type == 'send') {
                                $txn = 1;
                                $query = "SELECT * FROM transactions WHERE sender = $user_id ORDER BY transaction_date DESC";
                            } else if ($transaction_type == 'receive') {
                                $txn = 1;
                                $query = "SELECT * FROM transactions WHERE receiver = $user_id ORDER BY transaction_date DESC";
                            } else if ($transaction_type == 'deposit') {
                                $txn = 2;
                                $query = "SELECT * FROM deposit_history WHERE uid = $user_id ORDER BY date DESC";
                            } else if ($transaction_type == 'withdraw') {
                                $txn = 3;
                                $query = "SELECT * FROM withdrawal_history WHERE uid = $user_id ORDER BY withdrawal_date DESC";
                            } else if ($transaction_type == 'convert') {
                                $txn = 4;
                                $query = "SELECT * FROM conversion_history WHERE uid = $user_id ORDER BY date DESC";
                            }

                            $result = mysqli_query($con, $query);
                            if ($result && $txn == 1) {
                                if (mysqli_num_rows($result) > 0) {
                                    // Output table headers
                                    echo "<table border='1'>";
                                    echo "<tr>";
                                    echo "<th>Transaction ID</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>Sender</th>";
                                    echo "<th>Coin</th>";
                                    echo "<th>Amount</th>";
                                    echo "<th>Receiver</th>";
                                    echo "<th>Fees</th>";
                                    echo "<th>Date</th>";
                                    echo "</tr>";

                                    // Output data rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['txn_id']}</td>";
                                        echo "<td>$search_user</td>";
                                        echo "<td>{$row['sender']}</td>";
                                        echo "<td>{$row['coin']}</td>";
                                        echo "<td>{$row['amount']}</td>";
                                        echo "<td>{$row['receiver']}</td>";
                                        echo "<td>{$row['fees']}</td>";
                                        echo "<td>{$row['transaction_date']}</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    // No rows found in the result
                                    echo "No transactions found.";
                                }
                            } else if ($result && $txn == 2) {
                                if (mysqli_num_rows($result) > 0) {
                                    // Output table headers
                                    echo "<table border='1'>";
                                    echo "<tr>";
                                    echo "<th>Transaction ID</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>Coin</th>";
                                    echo "<th>User ID</th>";
                                    echo "<th>Transaction ID</th>";
                                    echo "<th>Amount</th>";
                                    echo "<th>Status</th>";
                                    echo "<th>Bonus</th>";
                                    echo "<th>Date</th>";
                                    echo "</tr>";

                                    // Output data rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['id']}</td>";
                                        echo "<td>$search_user</td>";
                                        echo "<td>{$row['coin']}</td>";
                                        echo "<td>{$row['uid']}</td>";
                                        echo "<td>{$row['txnid']}</td>";
                                        echo "<td>{$row['amount']}</td>";
                                        echo "<td>{$row['stats']}</td>";
                                        echo "<td>{$row['bonus']}</td>";
                                        echo "<td>{$row['date']}</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    // No rows found in the result
                                    echo "No deposit history found.";
                                }
                            } else if ($result && $txn == 3) {
                                if (mysqli_num_rows($result) > 0) {
                                    // Output table headers
                                    echo "<table border='1'>";
                                    echo "<tr>";
                                    echo "<th>Withdrawal ID</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>Coin</th>";
                                    echo "<th>Amount</th>";
                                    echo "<th>Address</th>";
                                    echo "<th>Withdrawal Status</th>";
                                    echo "<th>User ID</th>";
                                    echo "<th>Withdrawal Date</th>";
                                    echo "<th>Fees</th>";
                                    echo "</tr>";

                                    // Output data rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['withdrawal_id']}</td>";
                                        echo "<td>$search_user</td>";
                                        echo "<td>{$row['coin']}</td>";
                                        echo "<td>{$row['amount']}</td>";
                                        echo "<td>{$row['address']}</td>";
                                        echo "<td>{$row['withdrawal_status']}</td>";
                                        echo "<td>{$row['uid']}</td>";
                                        echo "<td>{$row['withdrawal_date']}</td>";
                                        echo "<td>{$row['fees']}</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    // No rows found in the result
                                    echo "No withdrawal history found.";
                                }
                            } else if ($result && $txn == 4) {
                                if (mysqli_num_rows($result) > 0) {
                                    // Output table headers
                                    echo "<table border='1'>";
                                    echo "<tr>";
                                    echo "<th>Conversion ID</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>From</th>";
                                    echo "<th>To</th>";
                                    echo "<th>Sent</th>";
                                    echo "<th>Received</th>";
                                    echo "<th>Fee</th>";
                                    echo "<th>User ID</th>";
                                    echo "<th>Date</th>";
                                    echo "</tr>";

                                    // Output data rows
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>{$row['id']}</td>";
                                        echo "<td>$search_user</td>";
                                        echo "<td>{$row['from']}</td>";
                                        echo "<td>{$row['to']}</td>";
                                        echo "<td>{$row['sent']}</td>";
                                        echo "<td>{$row['received']}</td>";
                                        echo "<td>{$row['fee']}</td>";
                                        echo "<td>{$row['uid']}</td>";
                                        echo "<td>{$row['date']}</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    // No rows found in the result
                                    echo "No conversion history found.";
                                }
                            } else {
                                echo "Error: " . mysqli_error($con);
                            }
                        } else {
                            // No user found with the provided username
                            echo "<tr><td colspan='7'>No user found with the provided username.</td></tr>";
                        }
                    } else {
                        // If no username is provided, show all transactions
                        // Construct the query based on transaction type
                        if ($transaction_type == 'send') {
                            $txn = 1;
                            $query = "SELECT * FROM transactions ORDER BY transaction_date DESC";
                        } else if ($transaction_type == 'receive') {
                            $txn = 1;
                            $query = "SELECT * FROM transactions ORDER BY transaction_date DESC";
                        } else if ($transaction_type == 'deposit') {
                            $txn = 2;
                            $query = "SELECT * FROM deposit_history ORDER BY date DESC";
                        } else if ($transaction_type == 'withdraw') {
                            $txn = 3;
                            $query = "SELECT * FROM withdrawal_history ORDER BY withdrawal_date DESC";
                        } else if ($transaction_type == 'convert') {
                            $txn = 4;
                            $query = "SELECT * FROM conversion_history ORDER BY date DESC";
                        }
                        $result = mysqli_query($con, $query);
                        if ($result && $txn == 1) {
                            if (mysqli_num_rows($result) > 0) {
                                // Output table headers
                                echo "<table border='1'>";
                                echo "<tr>";
                                echo "<th>Transaction ID</th>";
                                echo "<th>Sender</th>";
                                echo "<th>Coin</th>";
                                echo "<th>Amount</th>";
                                echo "<th>Receiver</th>";
                                echo "<th>Fees</th>";
                                echo "<th>Date</th>";
                                echo "</tr>";

                                // Output data rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['txn_id']}</td>";
                                    echo "<td>{$row['sender']}</td>";
                                    echo "<td>{$row['coin']}</td>";
                                    echo "<td>{$row['amount']}</td>";
                                    echo "<td>{$row['receiver']}</td>";
                                    echo "<td>{$row['fees']}</td>";
                                    echo "<td>{$row['transaction_date']}</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            } else {
                                // No rows found in the result
                                echo "No transactions found.";
                            }
                        } else if ($result && $txn == 2) {
                            if (mysqli_num_rows($result) > 0) {
                                // Output table headers
                                echo "<table border='1'>";
                                echo "<tr>";
                                echo "<th>Transaction ID</th>";
                                echo "<th>Coin</th>";
                                echo "<th>User ID</th>";
                                echo "<th>Transaction ID</th>";
                                echo "<th>Amount</th>";
                                echo "<th>Status</th>";
                                echo "<th>Bonus</th>";
                                echo "<th>Date</th>";
                                echo "</tr>";

                                // Output data rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['coin']}</td>";
                                    echo "<td>{$row['uid']}</td>";
                                    echo "<td>{$row['txnid']}</td>";
                                    echo "<td>{$row['amount']}</td>";
                                    echo "<td>{$row['stats']}</td>";
                                    echo "<td>{$row['bonus']}</td>";
                                    echo "<td>{$row['date']}</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                                // No rows found in the result
                                echo "No deposit history found.";
                            }
                        } else if ($result && $txn == 3) {
                            if (mysqli_num_rows($result) > 0) {
                                // Output table headers
                                echo "<table border='1'>";
                                echo "<tr>";
                                echo "<th>Withdrawal ID</th>";
                                echo "<th>Coin</th>";
                                echo "<th>Amount</th>";
                                echo "<th>Address</th>";
                                echo "<th>Withdrawal Status</th>";
                                echo "<th>User ID</th>";
                                echo "<th>Withdrawal Date</th>";
                                echo "<th>Fees</th>";
                                echo "</tr>";

                                // Output data rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['withdrawal_id']}</td>";
                                    echo "<td>{$row['coin']}</td>";
                                    echo "<td>{$row['amount']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['withdrawal_status']}</td>";
                                    echo "<td>{$row['uid']}</td>";
                                    echo "<td>{$row['withdrawal_date']}</td>";
                                    echo "<td>{$row['fees']}</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                                // No rows found in the result
                                echo "No withdrawal history found.";
                            }
                        } else if ($result && $txn == 4) {
                            if (mysqli_num_rows($result) > 0) {
                                // Output table headers
                                echo "<table border='1'>";
                                echo "<tr>";
                                echo "<th>Conversion ID</th>";
                                echo "<th>From</th>";
                                echo "<th>To</th>";
                                echo "<th>Sent</th>";
                                echo "<th>Received</th>";
                                echo "<th>Fee</th>";
                                echo "<th>User ID</th>";
                                echo "<th>Date</th>";
                                echo "</tr>";

                                // Output data rows
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['from']}</td>";
                                    echo "<td>{$row['to']}</td>";
                                    echo "<td>{$row['sent']}</td>";
                                    echo "<td>{$row['received']}</td>";
                                    echo "<td>{$row['fee']}</td>";
                                    echo "<td>{$row['uid']}</td>";
                                    echo "<td>{$row['date']}</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                                // No rows found in the result
                                echo "No conversion history found.";
                            }
                        }
                    }
                }
                ?>

            </table>
        </div>
    </div>
</body>

</html>