<?php
session_start();
if($_SESSION['adminlogin'] != true){
    header("Location: ../users/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'config.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            background: url('../images/bgcover.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            overflow-x: hidden;
            color: black;
            margin: auto;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        /* Main Content */
        .container {
            backdrop-filter: blur(9px);
            margin: 20px auto;
            width: 80%;
            /* Adjust the width as needed */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .view-user {
            width: 70%;
        }

        .add-user {
            width: 35%;
            /* Adjust the width as needed */
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            margin-bottom: 20px;
            width: 100%;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
        }

        td {
            background-color: gray;
            color: white;
        }

        h1 {
            background-color: gray;
            color: white;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        /* Delete Button */
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div style="display: flex; flex-direction:row; margin:auto;">
            <form method="post">
                <input style="font-size:25px;" type="submit" name="home" value="Home">
                <input style="font-size:25px;" type="submit" name="food" value="food">
                <input style="font-size:25px;" type="submit" name="user" value="user">
                <input style="font-size:25px;" type="submit" name="history" value="history">
                <input style="font-size:25px;" type="submit" name="logout" value="Log Out">
            </form>
            <?php
            if (isset($_POST['logout'])) {
                session_destroy();
                header('Location: ../users/login.php');
                exit;
            } else if (isset($_POST['home'])) {
                header('Location: ../index.php');
                exit;
            } else if (isset($_POST['food'])) {
                header('Location: food.php');
                exit;
            } else if (isset($_POST['user'])) {
                header('Location: user.php');
                exit;
            } else if (isset($_POST['history'])) {
                header('Location: history.php');
                exit;
            }
            ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="page-content">
            <form method="post">
                <input type="submit" name="reset" value="Reset Today Order">
            </form>
            <?php
            if (isset($_POST['reset'])) {
                $query = "UPDATE todaysorder SET quantity = '0' WHERE 1";
                $result = mysqli_query($con, $query);
                if ($result) {
                    echo "<script>alert('Todays Order History Reseted');</script>";
                }
            }
            ?>
            <h1>Total Users: <?php
                                $total_users = 0;
                                $q = "SELECT COUNT(id) AS total_users FROM employee";
                                $r = mysqli_query($con, $q);
                                if ($q && mysqli_num_rows($r) > 0) {
                                    $rw = mysqli_fetch_assoc($r);
                                    $total_users = $rw['total_users'];
                                }
                                echo $total_users;
                                ?></h1>

            <h1>Total Employee Salary: <?php
                                        $total_salary = 0;
                                        $q = "SELECT SUM(Salary) AS total_salary FROM employee";
                                        $r = mysqli_query($con, $q);
                                        if ($q && mysqli_num_rows($r) > 0) {
                                            $rw = mysqli_fetch_assoc($r);
                                            $total_salary = $rw['total_salary'];
                                        }
                                        echo $total_salary;
                                        ?></h1>

            <div class="order-history" style="width: 100%;">
                <h2>Order History</h2>
                <table>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Food ID</th>
                        <th>Food Name</th>
                        <th>Room Number</th>
                        <th>Order Date</th>
                    </tr>
                    <?php
                    // Fetch order history
                    $query = "SELECT orderhistory.EID, orderhistory.FID, orderhistory.FName, orderhistory.OrderDate, orderhistory.RoomNo, employee.Name FROM orderhistory LEFT JOIN employee ON employee.id = orderhistory.EID";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['EID'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['FID'] . "</td>";
                            echo "<td>" . $row['FName'] . "</td>";
                            echo "<td>" . $row['RoomNo'] . "</td>";
                            echo "<td>" . $row['OrderDate'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No orders found</td></tr>";
                    }
                    ?>
                </table>

            </div>
            <div class="todayorder">
                <h1>
                    Today Orders
                </h1>
                <table>
                    <tr>
                        <th>
                            Employee Id
                        </th>
                        <th>
                            Order quantity
                        </th>
                    </tr>
                    <?php
                    $todayquery = "select * from todaysorder";
                    $todaysresult = mysqli_query($con, $todayquery);

                    if ($todaysresult) {
                        if (mysqli_num_rows($todaysresult) > 0) { // Check if there are any rows
                            while ($row = mysqli_fetch_assoc($todaysresult)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='1'>No records found</td></tr>"; // Output a message if no records found
                        }
                    } else {
                        echo "<script>alert('Error fetching records');</script>";
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>

</body>

</html>