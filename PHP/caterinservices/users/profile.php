<!doctype html>
<html>
<?php
include '../admin/config.php';
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
}
$id = $_SESSION['id'];
if (isset($_POST['update'])) {
    // Check if all required form fields are set
    if (isset($_POST['phone'], $_POST['address'], $_POST['email'], $_POST['password'])) {
        // Assign form field values to variables
        $new_phone = $_POST['phone'];
        $new_address = $_POST['address'];
        $new_email = $_POST['email'];
        $new_pass = $_POST['password'];

        // Update the record in the database
        $sql = "UPDATE employee SET Phone='$new_phone', Address='$new_address', Email='$new_email', Pass='$new_pass' WHERE id='$id'";

        // Execute the update query
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Record update Failed');</script>";
        }
    } else {
        // Handle the case where one or more form fields are missing
        echo "<script>alert('One or more form fields are missing');</script>";
    }
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="display: flex; flex-direction:column">
    <div class="menu" style="width: 10vw; position:fixed; margin-top:20vh; margin-left:2vw;">
        <form method="post" style="display: flex; flex-direction:column">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="order" type="submit" value="Order">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="orderhistory" type="submit" value="OrderHistory">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="profile" type="submit" value="Profile">
            <input style="font-size: larger; border-radius:5px;" name="logout" type="submit" value="LogOut">
        </form>
        <?php
        if (isset($_POST['logout'])) {
            session_destroy();
            header('Location: login.php');
            exit;
        }
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
    <div class="profile">
        <h1>
            Employee Profile
        </h1>
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

            p {
                font-size: 25px;
                color: white;
                height: auto;
                margin-top: 0;
                margin-bottom: 0;
                padding-bottom: 0;
            }

            .profile {
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

            table td {
                background-color: #FFFFFF00;
                padding: 5px;
                width: max-content;
                height: auto;
                border: 1px white solid;
                justify-content: center;
            }
        </style>
        <table>
            <form method="post">
                <?php
                $query = "select * from employee where id=$id";
                $result = mysqli_query($con, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                ?>
                    <tr>
                        <td>
                            <p><b>Name: </b></p>
                        </td>
                        <td>
                            <p><b><?php echo $row['Name']; ?></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Phone no: </b></p>
                        </td>
                        <td>
                            <p><b><input style="font-size: 20px;" type="text" name="phone" value="<?php echo $row['Phone']; ?>"></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Email: </b></p>
                        </td>
                        <td>
                            <p><b><input style="font-size: 20px;" name="email" type="text" value="<?php echo $row['Email']; ?>"></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Address: </b></p>
                        </td>
                        <td>
                            <p><b><input style="font-size: 20px;" name="address" type="text" value="<?php echo $row['Address']; ?>"></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Password: </b></p>
                        </td>
                        <td>
                            <p><b><input style="font-size: 20px;" name="password" type="password" value="<?php echo $row['Pass']; ?>"></b></p>
                        </td>
                    </tr>
                    <td>
                        <p><b>Id: </b></p>
                    </td>
                    <td>
                        <p><b><?php echo $id; ?></b></p>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Position: </b></p>
                        </td>
                        <td>
                            <p><b><?php echo $row['Position']; ?></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Room No: </b></p>
                        </td>
                        <td>
                            <p><b><?php echo $row['OfficeRoom']; ?></b></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><b>Salary: </b></p>
                        </td>
                        <td>
                            <p><b><?php echo $row['Salary']; ?> BDT</b></p>
                        </td>
                    </tr>
                <?php } ?>
        </table>
        <input type="submit" name="update" value="Update Profile">
        </form>

    </div>
</body>

</html>