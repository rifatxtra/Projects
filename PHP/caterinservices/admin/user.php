<?php
session_start();
if($_SESSION['adminlogin'] != true){
    header("Location: ../users/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
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
            width: 100%;
            /* Adjust the width as needed */
            display: flex;
            justify-content: space-between;
        }

        .page-content {
            display: flex;
            flex-direction: column;
            align-items: center;
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
            <div class="view-user">
                <!-- User Search Form -->
                <h1>View Users</h1>
                <form method="post">
                    <label for="userId">Search User by ID:</label><br>
                    <input type="text" id="userId" name="userId"><br>
                    <input type="submit" name="searchUser" value="Search">
                </form>
                <!-- Display Users Table -->
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Office Room</th>
                        <th>Position</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    // PHP code to display users table
                    include 'config.php';
                    $id;
                    if (isset($_POST['addUser'])) {
                        // Retrieve form data and insert into database
                        $name = $_POST['add-name'];
                        $phone = $_POST['add-phone'];
                        $address = $_POST['add-address'];
                        $email = $_POST['add-email'];
                        $password = $_POST['add-pass'];
                        $officeno = $_POST['add-office-room'];
                        $position = $_POST['add-position'];
                        $salary = $_POST['add-salary'];
                        $sql = "INSERT INTO employee`( Name`, Phone, Address, Email, Pass, OfficeRoom, Position, Salary) VALUES ('$name','$phone','$address','$email','$password','$officeno','$position','$salary')";
                        if (mysqli_query($con, $sql)) {
                            $getid = "SELECT id FROM employee WHERE Phone='$phone' AND Email='$email'";
                            $idquery = mysqli_query($con, $getid);
                            
                            if ($idquery && mysqli_num_rows($idquery) > 0) {
                                $row = mysqli_fetch_assoc($idquery);
                                $eid = $row['id'];
                                
                                $todaysorderquery = "INSERT INTO todaysorder (id, quantity) VALUES ($eid, 0)";
                                $resultquory = mysqli_query($con, $todaysorderquery);
                                
                                if ($resultquory) {
                                    echo "<script>alert('New user added successfully');</script>";
                                } else {
                                    echo "<script>alert('Error: Unable to add user - Todaysorder query failed');</script>";
                                }
                            } else {
                                echo "<script>alert('Error: Unable to add user - ID query failed');</script>";  
                            }
                        } else {
                            echo "<script>alert('Error: Unable to add user - Employee insert query failed');</script>";
                        }
                    }
                    if (isset($_POST['searchUser'])) {
                        // Retrieve user ID from form and fetch user details from database
                        $userId = $_POST['userId'];
                        $sql = "SELECT * FROM employee WHERE id='$userId'";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            // Display user details
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['Name'] . "</td>";
                                echo "<td>" . $row['Phone'] . "</td>";
                                echo "<td>" . $row['Address'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['OfficeRoom'] . "</td>";
                                echo "<td>" . $row['Position'] . "</td>";
                                echo "<td>" . $row['Salary'] . "</td>";
                                echo "<td>";
                                echo "<form method='post'>";
                                echo "<input type='hidden' name='deleteUserId' value='" . $row['id'] . "'>";
                                echo "<input type='submit' class='delete-btn' name='deleteUser' value='Delete'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No user found with ID: $userId</td></tr>";
                        }
                    } else {
                        // Fetch and display all users
                        $sql = "SELECT * FROM employee";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $name=$row['Name']; $phone=$row['Phone']; $address=$row['Address'];
                                $salary=$row['Salary']; $email=$row['Email']; $office=$row['OfficeRoom'];
                                $position=$row['Position'];
                                echo "<tr>";
                                echo "<form method='post'>";
                                echo "<td> ".$row['id'] ."</td>";
                                echo "<td><input type='text' name='name' value='$name'></td>";
                                echo "<td><input type='text' name='phone' value='$phone'></td>";
                                echo "<td><input type='text' name='address' value='$address'></td>";
                                echo "<td><input type='text' name='email' value='$email'></td>";
                                echo "<td><input type='text' name='office' value='$office'></td>";
                                echo "<td><input type='text' name='position' value='$position'></td>";
                                echo "<td><input type='text' name='salary' value='$salary'></td>";
                                echo "<td>";
                                
                                echo "<input type='hidden' name='deleteUserId' value='" . $row['id'] . "'>";
                                echo "<input type='submit' class='delete-btn' name='deleteUser' value='Delete'>";
                                echo "<input type='hidden' name='updateUserId' value='" . $row['id'] . "'>";
                                echo "<input type='submit' class='update-btn' name='updateUser' value='update'>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No users found</td></tr>";
                        }
                    }
                    //update user
                    if (isset($_POST['updateUser'])) {
                        $updatename=$_POST['name'];
                        $updatephone=$_POST['phone'];
                        $updateaddress=$_POST['address'];
                        $updateemail=$_POST['email'];
                        $updateoffice=$_POST['office'];
                        $updateposition=$_POST['position'];
                        $updatesalary=$_POST['salary'];
                        $updateUserId = $_POST['updateUserId'];
                        $updateSql = "update employee set Name='$updatename',Phone='$updatephone',Address='$updateaddress',email='$updateemail',OfficeRoom='$updateoffice',position='$updateposition',salary='$updatesalary' WHERE id='$updateUserId'";
                        if (mysqli_query($con, $updateSql)) {
                            echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to update the user list
                        } else {
                            echo "<script>alert('Error: Unable to update user');</script>";
                        }
                    }
                    // Delete user if delete button clicked
                    if (isset($_POST['deleteUser'])) {
                        $deleteUserId = $_POST['deleteUserId'];
                        $deleteSql = "DELETE FROM employee WHERE id='$deleteUserId'";
                        if (mysqli_query($con, $deleteSql)) {
                            echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to update the user list
                        } else {
                            echo "<script>alert('Error: Unable to delete user');</script>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="add-user">
            <!-- Add User Form -->
            <h2>Add User</h2>
            <form method="post">
                <table>
                    <tr>
                        <td>
                            <label for="name">Name:</label>
                        </td>
                        <td>
                            <input type="text" name="add-name">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="name">Phone:</label>
                        </td>
                        <td><input type="text" name="add-phone"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Address:</label></td>
                        <td><input type="text" name="add-address"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Email:</label></td>
                        <td><input type="text" name="add-email"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Pass:</label></td>
                        <td><input type="password" name="add-pass"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Office Room:</label></td>
                        <td><input type="text" name="add-office-room"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Position:</label></td>
                        <td><input type="text" name="add-position"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Salary:</label></td>
                        <td><input type="text" name="add-salary"></td>
                    </tr>
                </table>
                <input type="submit" name="addUser" value="Add User">
            </form>
        </div>
    </div>

</body>

</html>