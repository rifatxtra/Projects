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
    <title>Overview</title>
    <link rel="stylesheet" href="../css/overview.css">
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
            }
            else if(isset($_POST['assets'])){
                header("Location: assets.php");
                exit;
            }
            else if(isset($_POST['overview'])){
                header("Location: overview.php");
                exit;
            }
            else if(isset($_POST['send'])){
                header("Location: send.php");
                exit;
            }
            else if(isset($_POST['receive'])){
                header("Location: receive.php");
                exit;
            }
            else if(isset($_POST['transaction'])){
                header("Location: transactions.php");
                exit;
            }
            else if(isset($_POST['convert'])){
                header("Location: conversion.php");
                exit;
            }
            else if(isset($_POST['conversion_history'])){
                header("Location: conversionhistory.php");
                exit;
            }
            else if(isset($_POST['deposit'])){
                header("Location: deposit.php");
                exit;
            }
            else if(isset($_POST['depsoit_history'])){
                header("Location: deposithistory.php");
                exit;
            }
            else if(isset($_POST['withdraw'])){
                header("Location: withdraw.php");
                exit;
            }
            else if(isset($_POST['withdraw_history'])){
                header("Location: withdrawhistory.php");
                exit;
            }
            else if(isset($_POST['profile'])){
                header("Location: profile.php");
                exit;
            }
            ?>
        </div>
            <div class="overview">
                <div class="h1">
                    <h2 style="color: white">Wallet Overview</h2>
                </div>
                <div class="balance">
                    <div class="block">
                        <div class="first_row">
                            <img src="../images/bitcoin.webp" alt="Error" height="25px" width="25px">
                            <h2>Bitcoin</h2>
                        </div>
                        <div class="line">
                            <p><?php echo "$btc_value"; ?> BTC</p>
                            <p class="usd_balane"><?php echo "&nbsp&nbsp";
                                                    echo "$" . $btc_value * $btc_price; ?> USD</p>
                        </div>
                    </div>
                    <div class="block">
                        <div class="first_row">
                            <img src="../images/ethereum.webp" alt="Error" height="25px" width="25px">
                            <h2>Etherium</h2>
                        </div>
                        <div class="line">
                            <p><?php
                                echo "$eth_value";
                                ?> ETH</p>
                            <p class="usd_balane"><?php
                                                    echo "&nbsp&nbsp $";
                                                    echo $eth_value * $eth_price;
                                                    ?> USD</p>
                        </div>
                    </div>
                    <div class="block">
                        <div class="first_row">
                            <img src="../images/solana.webp" alt="Error" height="25px" width="25px">
                            <h2>Solona</h2>
                        </div>
                        <div class="line">
                            <p><?php
                                echo "$sol_value";
                                ?> SOL</p>
                            <p class="usd_balane"><?php
                                                    echo "&nbsp&nbsp $";
                                                    echo $sol_value * $sol_price;
                                                    ?> USD</p>
                        </div>
                    </div>
                    <div class="block">
                        <div class="first_row">
                            <img src="../images/bnb-icon2_2x.webp" alt="Error" height="25px" width="25px">
                            <h2>Binance Coin</h2>
                        </div>
                        <div class="line">
                            <p><?php
                                echo "$bnb_value";
                                ?> BNB</p>
                            <p class="usd_balane"><?php
                                                    echo "&nbsp&nbsp $";
                                                    echo $bnb_value * $bnb_price;
                                                    ?> USD</p>
                        </div>
                    </div>
                    <!-- Remaining blocks for other currencies -->
                </div>
                <div class="transaction_summery">
                    <div class="transaction">
                        <h3>Recent Transactions</h3>
                        <table>
                            <tr>
                                <td>
                                    <p>Coin Name</p>
                                </td>
                                <td>
                                    <p>Sender UID</p>
                                </td>
                                <td>
                                    <p>Receiver UID</p>
                                </td>
                                <td>
                                    <p>Amount</p>
                                </td>
                            </tr>
                            <?php
                            $query = "SELECT sender, receiver, coin, amount FROM transactions ORDER BY transaction_date DESC LIMIT 5";
                            // Execute the query
                            $result = mysqli_query($con, $query);
                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . mysqli_error($con));
                            }
                            // Process the query result
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($userId == $row['sender'] || $userId == $row['receiver']) {
                                    echo '<tr>';
                                    echo '<td>' . strtoupper($row['coin']) . '</td>';
                                    echo '<td>' . $row['sender'] . '</td>';
                                    echo '<td>' . $row['receiver'] . '</td>';
                                    echo '<td>' . $row['amount'] . '</td>';
                                    echo '</tr>';
                                    if ($userId == $row['sender']) {
                                        if (($row['coin']) == "btc") {
                                            $sent += $btc_price * $row['amount'];
                                        } else if (($row['coin']) == "eth") {
                                            $sent += $eth_price * $row['amount'];
                                        } else if (($row['coin']) == "sol") {
                                            $sent += $sol_price * $row['amount'];
                                        } else if (($row['coin']) == "bnb") {
                                            $sent += $bnb_price * $row['amount'];
                                        }
                                    }
                                    if ($userId == $row['receiver']) {
                                        if (($row['coin']) == "btc") {
                                            $received += $btc_price * $row['amount'];
                                        } else if (($row['coin']) == "eth") {
                                            $received += $eth_price * $row['amount'];
                                        } else if (($row['coin']) == "sol") {
                                            $received += $sol_price * $row['amount'];
                                        } else if (($row['coin']) == "bnb") {
                                            $received += $bnb_price * $row['amount'];
                                        }
                                    }
                                }
                            }
                            if ($sent == 0 && $received == 0) {
                                echo '<tr><td colspan="3">No Transactions</td></tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <div class="summery">
                        <h3>Account History</h3>
                        <div class="text">
                            <p>Total Receive $<?php echo $received; ?></p>
                        </div>
                        <div class="text">
                            <p>Total Sent $<?php echo $sent; ?></p>
                        </div>
                    </div>
                </div>
                <div class="conversion">
                    <h3>Conversion Rate</h3>
                    <div class="btc_conversion">
                        <p>1 BTC= <?php echo $btc_price / $eth_price
                                    ?> ETH= <?php echo $btc_price / $sol_price ?> SOL= <?php echo $btc_price / $bnb_price ?> BNB</p>
                    </div>
                    <div class="eth_conversion">
                        <p>1 ETH= <?php echo $eth_price / $btc_price
                                    ?> BTC= <?php echo $eth_price / $sol_price ?> SOL= <?php echo $eth_price / $bnb_price ?> BNB</p>
                    </div>
                    <div class="sol_conversion">
                        <p>1 SOL= <?php echo $sol_price / $btc_price
                                    ?> BTC= <?php echo $sol_price / $eth_price ?> ETH= <?php echo $sol_price / $bnb_price ?> BNB</p>
                    </div>
                    <div class="bnb_conversion">
                        <p>1 BNB= <?php echo $bnb_price / $btc_price
                                    ?> BTC= <?php echo $bnb_price / $sol_price ?> SOL= <?php echo $bnb_price / $eth_price ?> BNB</p>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>