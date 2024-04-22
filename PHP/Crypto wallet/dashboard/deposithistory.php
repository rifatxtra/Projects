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
    <link rel="stylesheet" href="../css/deposithistory.css">
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
                <h1>Deposit History</h1>
            </div>
            <div class="deposit_history">
                <div>
                    <table>
                        <tr>
                            <td>
                                <p>Coin Name</p>
                            </td>
                            <td>
                                <p>Transaction ID</p>
                            </td>
                            <td>
                                <p>Amount</p>
                            </td>
                            <td>
                                <p>Satus</p>
                            </td>
                            <td>
                                <p>Bonus</p>
                            </td>
                            <td>
                                <p>Date</p>
                            </td>
                        </tr>
                        <?php
                        $query = "SELECT coin, uid, txnid, amount,stats,bonus,date FROM deposit_history where uid=$userId ORDER BY date DESC";
                        // Execute the query
                        $result = mysqli_query($con, $query);

                        // Check for errors
                        if (!$result) {
                            die("Query failed: " . mysqli_error($con));
                        }
                        // Process the query result
                        $transaction_found = false;
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($userId == $row['uid']) {
                                echo '<tr>';
                                echo '<td>' . strtoupper($row['coin']) . '</td>';
                                echo '<td>' . $row['txnid'] . '</td>';
                                echo '<td>' . $row['amount'] . '</td>';
                                echo '<td>' . $row['stats'] . '</td>';
                                echo '<td>' . $row['bonus'] . '</td>';
                                echo '<td>' . $row['date'] . '</td>';
                                echo '</tr>';
                                $transaction_found = true;
                            }
                        }

                        // If no transactions found, display "No Transactions"
                        if (!$transaction_found) {
                            echo '<tr><td colspan="6">No Transactions</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>