<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if($_SESSION['adminlogin'] != true){
    header("Location: ../users/login.php");
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Foods</title>
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

        .view-food {
            width: 50%;
        }

        .add-food {
            width: 50%;
            margin-left: 0;
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
        td{
            background-color: gray;
            color: white;
        }
        th {
            background-color: #333;
            color: #fff;
        }

        /* Delete and Update Button */
        .delete-btn, .update-btn {
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
            <div class="view-food">
                <!-- Food Search Form -->
                <h1>View Foods</h1>
                <form method="post">
                    <label for="foodId">Search Food by ID:</label><br>
                    <input type="text" id="foodId" name="foodId"><br>
                    <input type="submit" name="searchFood" value="Search">
                </form>
                <!-- Display Foods Table -->
                <table id="foodsTable">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Position</th>
                        <th>Max Quantity</th>
                        <th>Action</th>
                        <!-- Add more table headers here -->
                    </tr>
                    <?php
                    // PHP code to display foods table
                    include 'config.php';
                    $fi;
                    if (isset($_POST['searchFood'])) {
                        // Retrieve food ID from form and fetch food details from database
                        $foodId = $_POST['foodId'];
                        $sql = "SELECT * FROM foods WHERE id='$foodId'";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            // Display food details
                            while ($row = mysqli_fetch_assoc($result)) {
                                $fi=$row['id'];
                                $fnm=$row['FoodName'];
                                $foodImg=$row['FoodImage'];
                                $fpos=$row['Position'];
                                $fmaxqnt=$row['MaxQantity'];
                                echo "<form method='post'>";
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td><input type='text' name='food-name' value='$fnm'></td>";
                                echo "<td><input type='text' name='food-image' value='$foodImg'></td>";
                                echo "<td><input type='text' name='food-position' value='$fpos'></td>";
                                echo "<td><input type='text' name='food-max-quantity' value='$fmaxqnt'></td>";
                                echo "<td>";
                                echo "<input type='hidden' name='updateFoodId' value=' $fi '>";
                                echo "<input type='submit' class='update-btn' name='updateFood' value='Update'>";
                                echo "<input type='submit' class='delete-btn' name='deleteFood' value='Delete'>";
                                echo "</form>";
                                echo "</td>";
                                // Add more table cells for additional fields
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No food found with ID: $foodId</td></tr>";
                        }
                    } else {
                        // Fetch and display all foods
                        $sql = "SELECT * FROM foods";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $fi=$row['id'];
                                $fnm=$row['FoodName'];
                                $foodImg=$row['FoodImage'];
                                $fpos=$row['Position'];
                                $fmaxqnt=$row['MaxQantity'];
                                echo "<form method='post'>";
                                echo "<tr>";
                                echo "<td> $fi </td>";
                                echo "<td><input type='text' name='food-name' value='$fnm'></td>";
                                echo "<td><input type='text' name='food-image' value='$foodImg'></td>";
                                echo "<td><input type='text' name='food-position' value='$fpos'></td>";
                                echo "<td><input type='text' name='food-max-quantity' value='$fmaxqnt'></td>";
                                echo "<td>";
                                echo "<input type='hidden' name='updateFoodId' value='" . $row['id'] . "'>";
                                echo "<input type='submit' class='update-btn' name='updateFood' value='Update'>";
                                echo "<input type='submit' class='delete-btn' name='deleteFood' value='Delete'>";
                                echo "</form>";
                                echo "</td>";
                                // Add more table cells for additional fields
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No foods found</td></tr>";
                        }
                    }

                    // Update food if update button clicked
                    if (isset($_POST['updateFood'])) {
                        $updateFoodId = $_POST['updateFoodId'];
                        $foodName = $_POST['food-name'];
                        $foodImage = $_POST['food-image'];
                        $position = $_POST['food-position'];
                        $maxQuantity = $_POST['food-max-quantity'];
                        $updateSql = "UPDATE foods SET FoodName='$foodName', FoodImage='$foodImage', Position='$position', MaxQantity='$maxQuantity' WHERE id='$updateFoodId'";
                        if (mysqli_query($con, $updateSql)) {
                            echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to update the food list
                        } else {
                            echo "<script>alert('Error: Unable to update food');</script>";
                        }
                    }

                    // Delete food if delete button clicked
                    if (isset($_POST['deleteFood'])) {
                        $deleteFoodId = $_POST['deleteFoodId'];
                        $deleteSql = "DELETE FROM foods WHERE id='$fi'";
                        if (mysqli_query($con, $deleteSql)) {
                            echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to update the food list
                        } else {
                            echo "<script>alert('Error: Unable to delete food');</script>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="add-food">
            <!-- Add Food Form -->
            <h2>Add Food</h2>
            <form method="post">
                <table>
                    <tr>
                        <td>
                            <label for="foodname">Food Name:</label>
                        </td>
                        <td>
                            <input type="text" name="foodname">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="foodimage">Food Image:</label>
                        </td>
                        <td><input type="text" name="foodimage"></td>
                    </tr>
                    <tr>
                        <td><label for="foodposition">Position:</label></td>
                        <td><input type="text" name="foodposition"></td>
                    </tr>
                </table>
                <input type="submit" name="addFood" value="Add Food">
            </form>
            <?php
            if (isset($_POST['addFood'])) {
                // Retrieve form data and insert into database
                $fName = $_POST['foodname'];
                $fImage = $_POST['foodimage'];
                $E_position = $_POST['foodposition'];

                // $query = "INSERT INTO `foods`(`FoodName`, `FoodImage`, `Position`, `MaxQuantity`) VALUES ('$fName','$fImage','$E_position','$fmaxQuantity')";
                $qry="insert into foods (FoodName,FoodImage,Position) values('$fName','$fImage','$E_position')";
                $result = mysqli_query($con, $qry);
                if ($result) {
                    echo "<script>alert('New food added successfully');</script>";
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "<script>alert('Error: Unable to add food');</script>";
                }
            }
            
            ?>
        </div>
    </div>

</body>

</html>
