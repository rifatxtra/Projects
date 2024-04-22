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

// Retrieve user name
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

// Check if the form for selecting coin is submitted
if (isset($_POST['submit_coin'])) {
    // Handle form submission
    if (isset($_POST['select_coin'])) {
        $selected_coin = mysqli_real_escape_string($con, $_POST['select_coin']);
        $_SESSION['selected_coin'] = $selected_coin; // Store selected coin in session variable
        $coin = $selected_coin;
    } else {
        echo "<script>alert('Please select a currency.')</script>";
    }
}

// Check if the deposit submission form is submitted
if (isset($_POST['dep_submit'])) {
    // Handle deposit submission
    if (!empty($_SESSION['selected_coin'])) {
        $coin = $_SESSION['selected_coin'];
        $amount = $_POST['Amount_block'];
        $transaction = $_POST['Transaction_block'];
        $query = "SELECT bonus FROM deposit_address WHERE coin = '$coin'";
        $result = mysqli_query($con, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $dep_bonus = $row['bonus'];
            $bonus = ($dep_bonus * $amount) / 100;
            $query = "INSERT INTO deposit_history (coin, uid, txnid, amount, stats, bonus, date) 
                      VALUES ('$coin', '$userId', '$transaction', '$amount', 'pending', '$bonus', CURDATE())";
            $result = mysqli_query($con, $query);
            if ($result) {
                echo '<script>alert("Deposit Request Success")</script>';
            } else {
                echo '<script>alert("Deposit Request Failed")</script>';
            }
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "')</script>";
        }
    } else {
        echo "<script>alert('Please select a currency.')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/deposit.css">
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
                <h1>Deposit</h1>
            </div>
            <div class="deposit">
                <div class="deposit_block">
                    <form method="post" <?php echo isset($_POST['submit_coin']) ? 'style="display: none;"' : ''; ?>>
                        <p>Please Select Which Currency You want to Deposit</p>
                        <select name="select_coin">
                            <option value="">Select Currency</option>
                            <option value="btc">BTC</option>
                            <option value="eth">ETH</option>
                            <option value="sol">SOL</option>
                            <option value="bnb">BNB</option>
                        </select>
                        <input type="submit" name="submit_coin" value="Submit">
                    </form>
                    <?php
                    if (!empty($coin)) {
                        // Fetch deposit details for the selected coin
                        $query = "SELECT * FROM deposit_address WHERE coin = '$coin'";
                        $result = mysqli_query($con, $query);

                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            if ($row) {
                                // Display deposit details
                                echo "<div class='address-box'>";
                                echo "<p>Please Send Only $coin To This address</p>";
                                echo "<p>{$row['address']}</p>";
                                echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data={$row['address']}&amp;size=200x200' alt='QR Code'>";
                                echo "<p>Minimum Deposit Amount is {$row['min_amount']} $coin</p>";
                                echo "</div>";
                            } else {
                                echo "<script>alert('Address not found for $coin')</script>";
                            }
                            mysqli_free_result($result);
                        } else {
                            echo "<script>alert('Error: " . mysqli_error($con) . "')</script>";
                        }
                    }
                    ?>
                    <?php if (!empty($coin)) { ?>
                        <div class="dep_confirm_block">
                            <form method="post">
                                <p style="color: white;">Please Enter The Amount You Sent</p>
                                <input type="text" name="Amount_block">
                                <p style="color: white;">Please Enter The Transaction Has/ID of Your Transaction</p>
                                <input type="text" name="Transaction_block">
                                <input type="submit" value="Submit" name="dep_submit">
                            </form>
                        </div>
                    <?php } ?>
                </div>
                <div class="deposit_history">
                    <div><h2>Latest 5 Deposit History</h2></div>
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
                            $query = "SELECT coin, uid, txnid, amount,stats,bonus,date FROM deposit_history where uid=$userId ORDER BY date DESC limit 5";
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
    </div>
</body>

</html>