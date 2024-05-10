<?php
include '../admin/config.php';
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
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
    <title>Order Food</title>
</head>

<body
    style="margin: 0; padding: 0; width: 100vw; height: 100vh; display: flex; flex-direction: row; background: url('../images/bgcover.jpg'); background-repeat: no-repeat; background-size: cover; overflow-x: hidden; backdrop-filter:blur(5px);">
    <div class="menu"
        style="width: 10vw; position: fixed; display: flex; justify-content: center; align-items: center; margin-top:20vh; margin-left:2vw;">
        <form method="post" style="display: flex; flex-direction:column;">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="order" type="submit"
                value="Order">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="orderhistory" type="submit"
                value="OrderHistory">
            <input style="font-size: larger; border-radius:5px;margin-bottom: 8px;" name="profile" type="submit"
                value="Profile">
            <input style="font-size: larger; border-radius:5px;" name="logout" type="submit" value="LogOut">
        </form>
        <?php
        if (isset($_POST['logout'])) {
            session_destroy();
            header('Location: login.php');
            exit;
        } else if (isset($_POST['order'])) {
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
    <div class="main-part" style="width: 90vw; margin-left: 11vw; overflow-x:hidden">
        <h2
            style=" display: flex; justify-content: center; align-items: center; background-color: white; width:fit-content; margin:auto; margin-top: 5px;">
            Available Foods For You</h2>
        <div class="food-container">
            <?php
            // Fetch available food items for the current position
            $query = "SELECT * FROM Foods WHERE Position = '$position'";
            $result = mysqli_query($con, $query);
            echo '<table>';
            $tmp = 0;
            if (!$result) {
                die("Query failed: " . mysqli_error($con));
            } else {
                // Display each food item in a cell
                while ($row = mysqli_fetch_array($result)) {
                    if ($tmp == 0 || $tmp > 2)
                        echo '<tr><td>';
                    else
                        echo '<td>';
                    $tmp++;
                    ?>
                    <div class="food-item" style="width: 30vw; margin-bottom:7px;">
                        <div class="image">
                            <img src="..\images\<?php echo $row['FoodImage']; ?>" alt="<?php echo $row['FoodName']; ?>" width="90%"
                                style="border: 1px solid black;" height="300px"><br>
                        </div>
                        <div class="content"
                            style="display: flex; flex-direction:column; justify-content: center; align-items: center;">
                            <h3 style="background-color: white;"><?php echo $row['FoodName']; ?></h3>
                            <form method="post">
                                <input style="font-size: larger; border-radius:5px;" type="submit"
                                    name="<?php echo $row['id']; ?>" value="Order">
                            </form>
                        </div>
                        <?php
                        $fname = $row['FoodName'];
                        $fid = $row['id'];
                        $maxfood = $row['MaxQantity'];
                        if (isset($_POST[$fid])) {
                            $qr1 = "select quantity from todaysorder where id=$id";
                            $rlt1 = mysqli_query($con, $qr1);
                            if ($result && mysqli_num_rows($rlt1) > 0) {
                                $row = mysqli_fetch_assoc($rlt1);
                                if ($row['quantity'] < $maxfood) {
                                    $current = $row['quantity'];
                                    $current = $current + 1;
                                    $qr = "UPDATE todaysorder SET quantity=$current WHERE id='$fid'";
                                    $rlt = mysqli_query($con, $qr);
                                    $qr2 = "INSERT INTO orderhistory (`EID`, `FID`, `FName`, `RoomNo`, `OrderDate`) VALUES ('$id', '$fid', '$fname', '$officeroom', CURDATE())";
                                    $rlt2 = mysqli_query($con, $qr2);
                                    if ($rlt2)
                                        echo '<script>alert("Your Order Has been Received");</script>';
                                    else
                                        echo '<script>alert("Your Order Has not Received");</script>';
                                } else
                                    echo '<script>alert("Your Today Quota is Finished");</script>';
                            }
                        }
                        ?>
                    </div>
                    <?php
                    if ($tmp > 2) {
                        echo '</tr>';
                        $tmp = 0;
                    } else
                        echo '</td>';
                }
            }
            ?>
            </table>
        </div>
    </div>
</body>

</html>