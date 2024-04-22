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
if (isset($_POST['rcv_btc'])) {
    header("Location: receive.php?source=btc");
    exit;
} else if (isset($_POST['send_btc'])) {
    header("Location: send.php?source=btc");
    exit;
} else if (isset($_POST['rcv_eth'])) {
    header("Location: receive.php?source=eth");
    exit;
} else if (isset($_POST['send_eth'])) {
    header("Location: send.php?source=eth");
    exit;
} else if (isset($_POST['rcv_sol'])) {
    header("Location: receive.php?source=sol");
    exit;
} else if (isset($_POST['send_sol'])) {
    header("Location: send.php?source=sol");
    exit;
} else if (isset($_POST['rcv_bnb'])) {
    header("Location: receive.php?source=bnb");
    exit;
} else if (isset($_POST['send_bnb'])) {
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
    <link rel="stylesheet" href="../css/send.css">
    <title>Send</title>
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
                <h1>Send Crypto</h1>
            </div>
            <div class="columns">
                <div class="send" <?php echo !isset($_GET['source']) ? '' : 'style="display: none;"'; ?>>
                    <div>
                        <h3>Please Select Which Currency You want to Send</h3>
                        <form method="post">
                            <select name="select_coin">
                                <option value="btc">BTC</option>
                                <option value="eth">ETH</option>
                                <option value="sol">SOL</option>
                                <option value="bnb">BNB</option>
                            </select>
                            <p>Please Enter Receiver UID</p>
                            <p><input type="text" name="receiver_uid"></p>
                            <p>Please Enter Amount</p>
                            <p><input type="text" name="amount"></p>
                            <p><input type="submit" name="submit" value="Send"></p>
                        </form>
                        <?php
                        // Assuming $con is your database connection and $userId is the user's ID

                        if (isset($_POST['submit'])) {
                            // Check if receiver UID, select coin, and amount are provided
                            if (empty($_POST['receiver_uid']) || empty($_POST['select_coin']) || empty($_POST['amount'])) {
                                echo "<script>alert('Please enter receiver UID, select a coin, and provide an amount.')</script>";
                            } else {
                                // Retrieve inputs
                                $receiver_uid = $_POST['receiver_uid'];
                                $selected_coin = $_POST['select_coin'];

                                // Retrieve sender's balance for selected coin
                                $sender_balance_query = "SELECT $selected_coin FROM balance WHERE u_id = $userId";
                                $sender_balance_result = mysqli_query($con, $sender_balance_query);

                                if (!$sender_balance_result) {
                                    // Error in query execution
                                    echo "Error: " . mysqli_error($con);
                                } else {
                                    $sender_balance_row = mysqli_fetch_assoc($sender_balance_result);
                                    $sender_balance = $sender_balance_row[$selected_coin];

                                    // Retrieve amount from form
                                    $amount = $_POST['amount'];
                                    $fees = $amount * 0.005;
                                    // Check if sender has sufficient balance
                                    if ($sender_balance >= $amount + $fees) {
                                        // Deduct amount from sender's balance
                                        $update_sender_balance_query = "UPDATE balance SET $selected_coin = $selected_coin - ($amount-$fees) WHERE u_id = $userId";
                                        $update_sender_balance_result = mysqli_query($con, $update_sender_balance_query);
                                        $query = "INSERT INTO transactions (sender, coin, amount, receiver, fees, transaction_date) VALUES ('$userId', '$selected_coin', $amount, '$receiver_uid', $fees, CURRENT_DATE)";
                                        $result = mysqli_query($con, $query);

                                        if (!$update_sender_balance_result) {
                                            // Error updating sender's balance
                                            echo "Error: " . mysqli_error($con);
                                        } else {
                                            // Update receiver's balance
                                            $update_receiver_balance_query = "UPDATE balance SET $selected_coin = $selected_coin + $amount WHERE u_id = $receiver_uid";
                                            $update_receiver_balance_result = mysqli_query($con, $update_receiver_balance_query);

                                            if (!$update_receiver_balance_result) {
                                                // Error updating receiver's balance
                                                echo "Error: " . mysqli_error($con);
                                            } else {
                                                // Transaction successful
                                                echo "<script>alert('Transaction successful.')</script>";
                                            }
                                        }
                                    } else {
                                        // Insufficient balance
                                        echo "<script>alert('Insufficient balance.')</script>";
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="send" <?php echo isset($_GET['source']) ? '' : 'style="display: none;"'; ?>>
                <div>
                        <h3>Send <?php echo $_GET['source']?></h3>
                        <form method="post">
                            <p>Please Enter Receiver UID</p>
                            <p><input type="text" name="receiveruid"></p>
                            <p>Please Enter Amount</p>
                            <p><input type="text" name="amounts"></p>
                            <p><input type="submit" name="submits" value="Send"></p>
                        </form>
                        <?php
                        // Assuming $con is your database connection and $userId is the user's ID

                        if (isset($_POST['submits'])) {
                            // Check if receiver UID, select coin, and amount are provided
                            if (empty($_POST['receiveruid']) || empty($_POST['amounts'])) {
                                echo "<script>alert('Please enter receiver UID, select a coin, and provide an amount.')</script>";
                            } else {
                                // Retrieve inputs
                                $receiver_uid = $_POST['receiveruid'];
                                $selected_coin = $_GET['source'];

                                // Retrieve sender's balance for selected coin
                                $sender_balance_query = "SELECT $selected_coin FROM balance WHERE u_id = $userId";
                                $sender_balance_result = mysqli_query($con, $sender_balance_query);

                                if (!$sender_balance_result) {
                                    // Error in query execution
                                    echo "Error: " . mysqli_error($con);
                                } else {
                                    $sender_balance_row = mysqli_fetch_assoc($sender_balance_result);
                                    $sender_balance = $sender_balance_row[$selected_coin];

                                    // Retrieve amount from form
                                    $amount = $_POST['amounts'];
                                    $fees = $amount * 0.005;
                                    // Check if sender has sufficient balance
                                    if ($sender_balance >= $amount + $fees) {
                                        // Deduct amount from sender's balance
                                        $update_sender_balance_query = "UPDATE balance SET $selected_coin = $selected_coin - ($amount-$fees) WHERE u_id = $userId";
                                        $update_sender_balance_result = mysqli_query($con, $update_sender_balance_query);
                                        $query = "INSERT INTO transactions (sender, coin, amount, receiver, fees, transaction_date) VALUES ('$userId', '$selected_coin', $amount, '$receiver_uid', $fees, CURRENT_DATE)";
                                        $result = mysqli_query($con, $query);

                                        if (!$update_sender_balance_result) {
                                            // Error updating sender's balance
                                            echo "Error: " . mysqli_error($con);
                                        } else {
                                            // Update receiver's balance
                                            $update_receiver_balance_query = "UPDATE balance SET $selected_coin = $selected_coin + $amount WHERE u_id = $receiver_uid";
                                            $update_receiver_balance_result = mysqli_query($con, $update_receiver_balance_query);

                                            if (!$update_receiver_balance_result) {
                                                // Error updating receiver's balance
                                                echo "Error: " . mysqli_error($con);
                                            } else {
                                                // Transaction successful
                                                echo "<script>alert('Transaction successful.')</script>";
                                            }
                                        }
                                    } else {
                                        // Insufficient balance
                                        echo "<script>alert('Insufficient balance.')</script>";
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="transactions">
                    <h2>Transaction History</h2>
                    <table>
                        <?php
                        // Assuming $con is your database connection and $userId is the user's ID
                        $query = "SELECT * FROM transactions WHERE sender = $userId OR receiver = $userId ORDER BY transaction_date DESC limit 5";
                        $result = mysqli_query($con, $query);

                        if (!$result) {
                            echo "Error: " . mysqli_error($con);
                        } else {
                            // Display the table headers with attribute names
                            echo "<thead><tr>";
                            while ($fieldinfo = mysqli_fetch_field($result)) {
                                echo "<th>$fieldinfo->name</th>";
                            }
                            echo "</tr></thead><tbody>";

                            // Display the transaction data
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                foreach ($row as $value) {
                                    echo "<td>$value</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</tbody>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>