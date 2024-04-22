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
//send & rcv tracking
//send & rcv tracking
if(isset($_POST['rcv_btc'])) {
    header("Location: receive.php?source=btc");
    exit;
}
else if(isset($_POST['send_btc'])) {
    header("Location: send.php?source=btc");
    exit;
}
else if(isset($_POST['rcv_eth'])) {
    header("Location: receive.php?source=eth");
    exit;
}
else if(isset($_POST['send_eth'])) {
    header("Location: send.php?source=eth");
    exit;
}
else if(isset($_POST['rcv_sol'])) {
    header("Location: receive.php?source=sol");
    exit;
}
else if(isset($_POST['send_sol'])) {
    header("Location: send.php?source=sol");
    exit;
}
else if(isset($_POST['rcv_bnb'])) {
    header("Location: receive.php?source=bnb");
    exit;
}
else if(isset($_POST['send_bnb'])) {
    header("Location: send.php?source=bnb");
    exit;
}
//send & rcv tracking
//send & rcv tracking


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
    <title>Assets</title>
    <link rel="stylesheet" href="../css/assets.css">
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
        <div class="assets">
            <div class="asset_heading">
                <h3>Assets</h3>
            </div>
            <div class="total_assets">
                <h2>Total Balance</h2>
                <div class="total_bal">
                    <p><?php
                        echo $btc_price * $btc_value + $eth_price * $eth_value + $sol_price * $sol_value + $bnb_price * $bnb_value;
                        ?> USD</p>
                        <form method="post">
                            <input type="submit" name="rcv" value="Receive">
                            <input type="submit" name="send" value="Send">
                        </form>
                        <?php
                            if(isset($_POST['rcv'])) {
                                header("Location: receive.php");
                                exit;
                            }
                            else if(isset($_POST['send'])) {
                                header("Location: send.php");
                                exit;
                            }
                        ?>
                </div>
            </div>
            <div class="rows">
                <div class="asset_block">
                    <div class="intro_row">
                        <div class="intro_block">
                            <p>Coin</p>
                        </div>
                        <div class="intro_block">
                            <p>Amount</p>
                        </div>
                        <div class="intro_block">
                            <p>Action</p>
                        </div>
                    </div>
                    <div class="balance_row">
                        <div class="intro">
                            <div class="img">
                                <img src="../images/bitcoin.webp" alt="error" height="40px" width="40px">
                            </div>
                            <div class="name">
                                <p><b>BTC</b></p>
                                <p>Bitcoin</p>
                            </div>
                        </div>
                        <div class="amount">
                            <p><?php echo $btc_value; ?> BTC</p>
                            <p><?php echo $btc_value * $btc_price; ?> USD</p>
                        </div>
                        <div class="btn">
                        <form method="post">
                            <input type="submit" name="rcv_btc" value="Receive">
                            <input type="submit" name="send_btc" value="Send">
                        </form>
                        <?php
                            if(isset($_POST['rcv_btc'])) {
                                header("Location: receive.php?source=btc");
                                exit;
                                header("Refresh: 0; url=".$_SERVER['PHP_SELF']."?source=$source");
                            }
                            else if(isset($_POST['send_btc'])) {
                                header("Location: send.php?source=btc");
                                exit;
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="asset_block">
                    <div class="balance_row">
                        <div class="intro">
                            <div class="img">
                                <img src="../images/ethereum.webp" alt="error" height="40px" width="40px">
                            </div>
                            <div class="name">
                                <p><b>ETH</b></p>
                                <p>Etherium</p>
                            </div>
                        </div>
                        <div class="amount">
                            <p><?php echo $eth_value; ?> ETH</p>
                            <p><?php echo $eth_value * $eth_price; ?> USD</p>
                        </div>
                        <div class="btn">
                        <form method="post">
                            <input type="submit" name="rcv_eth" value="Receive">
                            <input type="submit" name="send_eth" value="Send">
                        </form>
                        <?php
                            if(isset($_POST['rcv_eth'])) {
                                header("Location: receive.php?source=eth");
                                exit;
                            }
                            else if(isset($_POST['send_eth'])) {
                                header("Location: send.php?source=eth");
                                exit;
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="asset_block">
                    <div class="balance_row">
                        <div class="intro">
                            <div class="img">
                                <img src="../images/solana.webp" alt="error" height="40px" width="40px">
                            </div>
                            <div class="name">
                                <p><b>SOL</b></p>
                                <p>Solona</p>
                            </div>
                        </div>
                        <div class="amount">
                            <p><?php echo $sol_value; ?> SOL</p>
                            <p><?php echo $sol_value * $sol_price; ?> USD</p>
                        </div>
                        <div class="btn">
                        <form method="post">
                            <input type="submit" name="rcv_sol" value="Receive">
                            <input type="submit" name="send_sol" value="Send">
                        </form>
                        <?php
                            if(isset($_POST['rcv_sol'])) {
                                header("Location: receive.php?source=sol");
                                exit;
                            }
                            else if(isset($_POST['send_sol'])) {
                                header("Location: send.php?source=sol");
                                exit;
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="asset_block">
                    <div class="balance_row">
                        <div class="intro">
                            <div class="img">
                                <img src="../images/bnb-icon2_2x.webp" alt="error" height="40px" width="40px">
                            </div>
                            <div class="name">
                                <p><b>BNB</b></p>
                                <p>Binance coin</p>
                            </div>
                        </div>
                        <div class="amount">
                            <p><?php echo $bnb_value; ?> BNB</p>
                            <p><?php echo $bnb_value * $bnb_price; ?> USD</p>
                        </div>
                        <div class="btn">
                        <form method="post">
                            <input type="submit" name="rcv_bnb" value="Receive">
                            <input type="submit" name="send_bnb" value="Send">
                        </form>
                        <?php
                            if(isset($_POST['rcv_bnb'])) {
                                header("Location: receive.php?source=bnb");
                                exit;
                            }
                            else if(isset($_POST['send_bnb'])) {
                                header("Location: send.php?source=bnb");
                                exit;
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>