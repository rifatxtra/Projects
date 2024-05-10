<?php
include '../admin/config.php';
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
}
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$officeroom = $_SESSION['officeroom'];
$position = $_SESSION['position'];
$id = $_SESSION['id'];
$phone = $_SESSION['phone'];
$address = $_SESSION['address'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderHistory</title>
    <style>
        p {
            font-size: 25px;
        }

        .content {
            width: 80%;
            display: flex;
            justify-items: center;
            align-items: center;
            flex-direction: column;
            margin: auto;
        }

        table {
            padding: 6px;
            width: auto;
            margin-right: 8%;
            margin-bottom: 8%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="menu" style="width: 10vw; position:fixed; margin-top:20vh; margin-left:2vw;">
        <form method="post" style="display: flex; flex-direction:column">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="order" type="submit"
                value="Order">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="orderhistory" type="submit"
                value="OrderHistory">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="profile" type="submit"
                value="Profile">
            <input style="font-size: larger; border-radius:5px;" name="logout" type="submit" value="LogOut">
        </form>
        <?php
        if (isset($_POST['logout']))
            session_destroy();
        else if (isset($_POST['order'])) {
            header('Location: order.php');
            exit;
        } else if (isset($_POST['orderhistory'])) {
            header('Location: orderhistory.php');
            exit;
        } else if (isset($_POST['profile'])) {
            header('Location: profile.php');
            exit;
        }
        ?>
    </div>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('../images/bgcover.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(4px);
        }

        h1 {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
        }

        .content {
            width: 80%;
            display: flex;
            justify-items: center;
            align-items: center;
            flex-direction: column;
            margin: auto;
        }

        table {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 6px;
            width: auto;
            box-shadow: 0px 0px 5px rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);

        }

        table td,
        table th {
            background-color: #FFFFFF00;
            padding: 5px;
            font-size: 25px;
            width: max-content;
            height: auto;
            color: white;
            border: 1px white solid;
            justify-content: center;

        }
    </style>
    <div class="content">
        <h1>Order History</h1>
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Food Id</th>
                <th>Order ID</th>
                <th>Food Name</th>
                <th>Date</th>
            </tr>
            <?php
            // Fetch order history based on employee ID
            $query = "SELECT EID, ID, FID, FName, OrderDate FROM orderhistory WHERE EID = $id";
            $result = mysqli_query($con, $query);

            // Loop through the results and display each row in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['EID'] . "</td>";
                echo "<td>" . $row['FID'] . "</td>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['FName'] . "</td>";
                echo "<td>" . $row['OrderDate'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>