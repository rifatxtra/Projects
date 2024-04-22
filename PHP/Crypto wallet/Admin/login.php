<?php

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
} else {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Log In</title>
</head>

<body>
    <?php
    include('../admin/config.php');
    if ($con->connect_error) {
        header("Location: error/error.php");
        exit;
    }
    ?>
    <div class="main">
        <h1>Log In</h1>
        <form method="POST">
            <div class="line">
                <p>Enter Your username</p>
                <input type="text" name="username" placeholder="Username">
                <div>
                    <div class="line">
                        <p>Enter Your Password</p>
                        <input type="password" name="password" placeholder="******">
                    </div>
                    <div class="button">
                        <input type="submit" name="sign_in" value="Sign In">
                    </div>
        </form>
    </div>
    <?php
    if (isset($_POST["sign_in"])) {
        // Retrieve form data
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Validate username and password
        if (!empty($username) && !empty($password)) {
            // Prepare SQL query to retrieve user data based on the provided username
            $sql = "SELECT * FROM admins WHERE User_Name = '$username'";
            $result = mysqli_query($con, $sql);

            // Check if the query executed successfully
            if ($result) {
                // Check if any matching user is found
                if (mysqli_num_rows($result) == 1) {
                    // Fetch user data
                    $row = mysqli_fetch_assoc($result);

                    // Verify the password
                    if (password_verify($password, $row['Password'])) {
                        // Password is correct, redirect to the home page or dashboard
                        // For now, let's redirect to a hypothetical homepage.php
                        $userId = $row['User_ID'];
                        session_start();
                        $_SESSION['admin'] = $userId;
                        header("Location: profile.php");
                        exit;
                    } else {
                        // Password is incorrect
                        echo '<script type="text/javascript">';
                        echo 'alert("Incorrect password. Please try again.")';
                        echo '</script>';
                    }
                } else {
                    // No user found with the provided username
                    echo '<script type="text/javascript">';
                    echo 'alert("User not found. Please check your username and try again or sign up for a new account.")';
                    echo '</script>';
                }
            } else {
                // Error executing the SQL query
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
        } else {
            // Username or password fields are empty
            echo '<script type="text/javascript">';
            echo 'alert("Please enter both username and password.")';
            echo '</script>';
        }
    }

    ?>
</body>

</html>