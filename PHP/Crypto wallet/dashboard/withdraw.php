<?php
include('../admin/config.php');
if ($con->connect_error) {
    header("Location: error/error.php");
    exit;
}
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    echo '<script type="text/javascript">';
    echo 'alert("Please Log in First")';
    echo '</script>';
    header("Location: ../login/index.php");
    exit;
}

// Retrieve user ID from session
$userId = $_SESSION['user_id'];
$dep_bonus = 0.00;
$selected_coin = "";
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

// Query to retrieve user data for the specific UID
$query = "SELECT btc, eth, sol, bnb
          FROM balance
          WHERE u_id = '$userId'";

$result = mysqli_query($con, $query);
$fee;
$address;
$btc_value;
$eth_value;
$sol_value;
$bnb_value;
$btc_price;
$eth_price;
$sol_price;
$bnb_price;
$sent = 0;
$send_bal;
$rcv_bal;
$received = 0;
$new_phone_no = "";

if ($result) {
    // Check if any rows are returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $userData = mysqli_fetch_assoc($result);

        // Output user data
        $btc_value = $userData['btc'];
        $eth_value = $userData['eth'];
        $sol_value = $userData['sol'];
        $bnb_value = $userData['bnb'];
    } else {
        // No rows found for the specific UID
        echo "No data found for the specified user ID.";
    }
} else {
    // Error executing the query
    echo "Error: " . mysqli_error($con);
}
$query = "SELECT coin_id, coin_name,price FROM prices";
$result = mysqli_query($con, $query);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['coin_id'] == 1) $btc_price = $row['price'];
            else if ($row['coin_id'] == 2) $eth_price = $row['price'];
            else if ($row['coin_id'] == 3) $sol_price = $row['price'];
            else $bnb_price = $row['price'];
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/withdraw.css">
    <title>Deposit</title>
</head>

<body>
    <div class="container">
        <div class="menu">
            <form method="post">
                <input type="submit" name="overview" value="Overview">
                <input type="submit" name="assets" value="Assets">
                <input type="submit" name="send" value="Send">
                <input type="submit" name="receive" value="Receive">
                <input type="submit" name="transaction" value="Transactions">
                <input type="submit" name="convert" value="Convert">
                <input type="submit" name="conversion_history" value="Conversion History">
                <input type="submit" name="deposit" value="Deposit">
                <input type="submit" name="depsoit_history" value="Deposit History">
                <input type="submit" name="withdraw" value="Withdraw">
                <input type="submit" name="withdraw_history" value="Withdraw_history">
                <input type="submit" name="profile" value="Profile">
                <input type="submit" name="signout" value="Sign Out">
            </form>
            <?php
            if (isset($_POST['signout'])) {
                $_SESSION = array();
                // Destroy the session
                session_destroy();
                header("Location: ../login/index.php");
                exit;
            } else if (isset($_POST['assets'])) {
                header("Location: assets.php");
                exit;
            } else if (isset($_POST['overview'])) {
                header("Location: overview.php");
                exit;
            } else if (isset($_POST['send'])) {
                header("Location: send.php");
                exit;
            } else if (isset($_POST['receive'])) {
                header("Location: receive.php");
                exit;
            } else if (isset($_POST['transaction'])) {
                header("Location: transactions.php");
                exit;
            } else if (isset($_POST['convert'])) {
                header("Location: conversion.php");
                exit;
            } else if (isset($_POST['conversion_history'])) {
                header("Location: conversionhistory.php");
                exit;
            } else if (isset($_POST['deposit'])) {
                header("Location: deposit.php");
                exit;
            } else if (isset($_POST['depsoit_history'])) {
                header("Location: deposithistory.php");
                exit;
            } else if (isset($_POST['withdraw'])) {
                header("Location: withdraw.php");
                exit;
            } else if (isset($_POST['withdraw_history'])) {
                header("Location: withdrawhistory.php");
                exit;
            } else if (isset($_POST['profile'])) {
                header("Location: profile.php");
                exit;
            }
            ?>
        </div>
        <div class="content">
            <div class="heading">
                <h1>Withdraw</h1>
            </div>
            <div class="withdraw">
                <div class="withdraw_container">
                    <div class="w_half">
                        <h2>Withdraw</h2>
                        <form method="post">
                            <div>
                                <label for="coin">Select Coin:</label>
                                <select id="coin" name="coin" required>
                                    <option value="btc">Bitcoin</option>
                                    <option value="eth">Ethereum</option>
                                    <option value="sol">Solona</option>
                                    <option value="bnb">Binance Coin</option>
                                    <!-- Add more options for other coins if needed -->
                                </select>
                            </div>
                            <div>
                                <label for="amount">Amount:</label>
                                <input type="number" id="amount" name="amount" min="0" step="any" required>
                            </div>
                            <div>
                                <label for="address">Withdrawal Address:</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                            <div>
                                <input type="submit" name="submit_withdraw" value="Withdraw">
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['submit_withdraw'])) {
                            // Get form data
                            $coin = $_POST["coin"];
                            $amount = $_POST["amount"];
                            $address = $_POST["address"];
                            $withdrawalFees = 0.00;

                            // Fetch withdrawal fees for the selected coin
                            $query = "SELECT withdrawal_fess FROM deposit_address WHERE coin = '$coin'";
                            $result = mysqli_query($con, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $withdrawalFees = $row['withdrawal_fess'] / 100;
                                echo "Withdrawal Fees: $withdrawalFees";
                                // Fetch balance of the selected coin for the user
                                $query = "SELECT $coin FROM balance WHERE u_id = '$userId'";
                                $result = mysqli_query($con, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    $w_balance = $row[$coin];

                                    // Check if user has sufficient balance for withdrawal
                                    if ($w_balance >= ($amount + $withdrawalFees)) {
                                        // Deduct withdrawal fees from withdrawal amount
                                        $totalAmount = $amount + $withdrawalFees;

                                        // Update balance after deduction
                                        $newBalance = $w_balance - $totalAmount;
                                        $updateQuery = "UPDATE balance SET $coin = $newBalance WHERE u_id = '$userId'";
                                        $updateResult = mysqli_query($con, $updateQuery);

                                        // Insert withdrawal history
                                        $insertQuery = "INSERT INTO withdrawal_history (coin, amount, address, withdrawal_status, withdrawal_date, uid, fees) VALUES ('$coin', '$amount', '$address', 'pending', CURDATE(), '$userId', '$withdrawalFees')";
                                        $insertResult = mysqli_query($con, $insertQuery);

                                        if ($updateResult && $insertResult) {
                                            echo '<script>alert("Withdrawal request submitted successfully.")</script>';
                                        } else {
                                            echo '<script>alert("Error submitting withdrawal request.")</script>';
                                        }
                                    } else {
                                        echo '<script>alert("Insufficient Balance")</script>';
                                    }
                                } else {
                                    echo '<script>alert("No coin found for the specified user.")</script>';
                                }
                            } else {
                                echo '<script>alert("Withdrawal fees not found for the selected coin.")</script>';
                            }
                        }
                        ?>
                    </div>
                    <div class="w_lhalf">
                        <?php
                        // Assuming $userId is available
                        // You may need to retrieve user ID from the session or somewhere else
                        // SQL query to fetch the latest 5 transactions of the logged-in user
                        $query = "SELECT * FROM withdrawal_history WHERE uid = '$userId' ORDER BY withdrawal_date DESC LIMIT 5";
                        $result = mysqli_query($con, $query);
                        // Check if there are any transactions
                        if (mysqli_num_rows($result) > 0) {
                            echo "<h2>Latest 5 Withdrwals</h2>";
                            echo "<table border='1'>";
                            echo "<tr><th><p>Transaction ID</p></th><th><p>Coin</p></th><th><p>Amount</p></th><th><p>Address</p></th><th><p>Withdrawal Date</p></th><th><p>Status</p></th><th><p>Fees</p></th></tr>";
                            // Loop through each transaction
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><p>" . $row['withdrawal_id'] . "</p></td>";
                                echo "<td><p>" . $row['coin'] . "</p></td>";
                                echo "<td><p>" . $row['amount'] . "</p></td>";
                                echo "<td><p>" . $row['address'] . "</p></td>";
                                echo "<td><p>" . $row['withdrawal_date'] . "</p></td>";
                                echo "<td><p>" . $row['withdrawal_status'] . "</p></td>";
                                echo "<td><p>" . $row['fees'] . "</p></td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p style='color:white; text-align:center';>No transactions found.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>