<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>

<?php
    include('../admin/config.php');
    if($con->connect_error){
        header("Location: error/error.php");
        exit;
    }
    $first_name = "";
    $last_name = "";
    $user_name = "";
    $phone_no = "";
    $email = "";
    $first_password = "";
    $confirm_password = "";
    // Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    
}
else{
    header("Location: ../dashboard/index.php");
    exit;
}

?>
<div class="main">
    <h1>Sign Up Form</h1>
    <form method="post">
        <div class="name">
            <div class="first_name">
                <p>Enter Your First Name</p>
                <input type="text" name="first_name" placeholder="First Name">
            </div>
            <div class="last_name">
            <p>Enter Your Last Name</p>
                <input type="text" name="last_name" placeholder="Last Name">
            </div>
        </div>
        <div class="user_name_and_phone_no">
            <div class="user_name">
                <p>Enter Username</p>
                <input type="text" name="user_name" placeholder="Username">
            </div>
            <div class="phone_no">
                <p>Enter 11 Digit Phone Number</p>
                <input type="number" name="phone_no" placeholder="Phone No">
            </div>
        </div>
        <div class="email">
            <p>Enter Email Address</p>
            <input type="email" name="email" placeholder="E-Mail">
        </div>
        <div class="password">
            <div class="first_password">
                <p>Enter a Password</p>
                <input type="password" name="first_password" placeholder="******">
            </div>
            <div class="confirm_password">
                <p>Enter Password Again</p>
                <input type="password" name="confirm_password" placeholder="******">
            </div>
        </div>
        <div class="submit">
            <input type="submit" value="Submit" name="submit">
        </div>
    </form>
    <div class="move_to_log_in_page">
        <p>Already have an account?</p>
        <a href="../login/index.php"><button>Log In</button></a>
    </div>
</div>
<?php
    if (isset($_POST["submit"])) {
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $user_name = $_POST["user_name"];
        $phone_no = $_POST["phone_no"];
        $email = $_POST["email"];
        $first_password = $_POST["first_password"];
        $confirm_password = $_POST["confirm_password"];
        
        if ($first_name != "") {
            if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
                echo '<script type="text/javascript">';
                echo 'alert("Only letters and white space allowed in First Name")';
                echo '</script>';
            } else {
                if ($last_name != "") {
                    if (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
                        echo '<script type="text/javascript">';
                        echo 'alert("Only letters and white space allowed in Last Name")';
                        echo '</script>';
                    } else {
                        if ($user_name != "") {
                            $check_username = "SELECT * FROM users WHERE User_Name='$user_name'";
                            $result = mysqli_query($con, $check_username);
                            if (mysqli_num_rows($result) > 0) {
                                echo '<script type="text/javascript">';
                                echo 'alert("Username is not available. Please choose a different one.")';
                                echo '</script>';
                            } else {
                                if (preg_match('/^0\d{10}$/', $phone_no)) {
                                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        if ($first_password == $confirm_password) {
                                            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $first_password)) {
                                                echo '<script type="text/javascript">';
                                                echo 'alert("Password must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long")';
                                                echo '</script>';
                                            } else {
                                                $hashed_password = password_hash($first_password, PASSWORD_DEFAULT);
                                                $insert = "INSERT INTO users (First_Name, Last_Name, User_Name, Phone_No, Email, Password) 
                                                VALUES ('$first_name', '$last_name', '$user_name', '$phone_no', '$email', '$hashed_password')";
                                                $result = mysqli_query($con, $insert);
                                                if ($result) {
                                                    // Insertion successful, get the last inserted user ID
                                                $userId = mysqli_insert_id($con);
                                                $insert="INSERT INTO balance VALUES($userId,0,0,0,0)";
                                                $result = mysqli_query($con, $insert);
                                                header("Location: ../login/index.php");
                                                exit;
                                                } else {
                                                    echo "Error: " . $insert . "<br>" . mysqli_error($con);
                                                }
                                            }
                                        } else {
                                            echo '<script type="text/javascript">';
                                            echo 'alert("Password Doesn\'t Match")';
                                            echo '</script>';
                                        }
                                    } else {
                                        echo '<script type="text/javascript">';
                                        echo 'alert("Invalid email address")';
                                        echo '</script>';
                                    }
                                } else {
                                    echo '<script type="text/javascript">';
                                    echo 'alert("Phone number must be 11 digits long and start with 0")';
                                    echo '</script>';
                                }
                            }
                        } else {
                            echo '<script type="text/JavaScript">';
                            echo 'alert("Enter User Name")';
                            echo '</script>';
                        }
                    }
                } else {
                    echo '<script type="text/JavaScript">';
                    echo 'alert("Enter Last Name")';
                    echo '</script>';
                }
            }
        } else {
            echo '<script type="text/JavaScript">';
            echo 'alert("Enter First Name")';
            echo '</script>';
        }
    }
?>

</body>
</html>