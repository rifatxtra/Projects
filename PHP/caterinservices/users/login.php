<?php
include '../admin/config.php';
session_start();
// if (!isset($_SESSION['login'])) {
//     $_SESSION['login'] = false;
// } else {
//     if ($_SESSION['login'] == true) {
//         header('Location: order.php');
//     }
// }

$selected_option = ""; // Initialize outside of the if block
// $emp_id="";$emp_pass="";
if (isset($_POST['submit'])) {
    if (isset($_POST['login-track'])) {
        $selected_option = $_POST['login-track'];
    }
    $emp_id = $_POST['emp-id'];
    $emp_pass = $_POST['emp-pass'];

    if ($selected_option == "Admin") {
        $query = "SELECT * FROM admin WHERE id = $emp_id";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($emp_pass == $row['Pass']) {
                echo '<script>alert("Log in Successful");</script>';
                $_SESSION['adminlogin'] = true;
                $_SESSION['adminid'] = $row['id'];
                header('Location: ../admin/user.php');
                exit;
            } else {
                echo '<script>alert("Password is wrong");</script>';
            }
        } else {
            echo '<script>alert("Invalid Admin ID");</script>';
        }
    } else {
        $query = "SELECT * FROM employee WHERE id = $emp_id";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($emp_pass == $row['Pass']) {
                echo '<script>alert("Log in Successful");</script>';
                $_SESSION['login'] = true;
                $_SESSION['name'] = $row['Name'];
                $_SESSION['phone'] = $row['Phone'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['pass'] = $row['Pass'];
                $_SESSION['position'] = $row['Position'];
                $_SESSION['officeroom'] = $row['OfficeRoom'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['address'] = $row['Residence'];
                $_SESSION['salary'] = $row['Salary'];
                $name = $_SESSION['name'];
                header('Location: order.php');
                exit;
            } else {
                echo '<script>alert("Password is wrong");</script>';
            }
        } else {
            echo '<script>alert("Invalid Employee ID");</script>';
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            vertical-align: middle;
            margin: auto;
            margin-top: 10%;
            background-image: url('../images/bgcover.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            backdrop-filter: blur(4px);
        }

        .form-login {
            background-color: rgb(255, 200, 132);
        }

        form {
            width: 50vw;
            display: flex;
            flex-direction: column;
            justify-items: center;
            justify-content: center;
            align-content: center;
            font-size: 25px;
            /* margin-left: 10%; */
            padding: 7px;
        }

        h1 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form label,
        form input {
            margin-bottom: 5px;
        }

        form input {
            font-size: x-large;
        }

        input[type="submit"] {
            width: fit-content;
        }

        .btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Custom styling for radio buttons */
        input[type="radio"] {
            display: none;
            /* Hide the default radio button */
        }

        label.radio-label {
            position: relative;
            padding-left: 30px;
            margin-right: 20px;
            cursor: pointer;
        }

        label.radio-label:before {
            content: "";
            position: absolute;
            left: 0;
            top: 3px;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            background-color: white;
        }

        input[type="radio"]:checked+label.radio-label:before {
            background-color: #2196F3;
        }

        input[type="radio"]:checked+label.radio-label:after {
            content: "";
            position: absolute;
            top: 9px;
            left: 5px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="form-login">
        <h1>Log In</h1>
        <form method="post">
            <label>Log in As:</label><br>
            <input type="radio" id="admin" name="login-track" value="Admin" <?php if (isset($_POST['login-track']) && $_POST['login-track'] == 'Admin')  ?>>
            <label class="radio-label" for="admin">Admin</label>
            <input type="radio" id="user" name="login-track" value="User" <?php if (isset($_POST['login-track']) && $_POST['login-track'] == 'User')  ?>>
            <label class="radio-label" for="user">User</label><br>

            <label for="emp-id">Enter Your <?php echo isset($_POST['login-track']) ? $_POST['login-track'] : ''; ?> ID</label>
            <input type="text" name="emp-id" required><br>

            <label for="emp-pass">Enter Your Password</label>
            <input type="password" name="emp-pass" required><br>

            <input type="submit" name="submit" value="Log In">
        </form>
    </div>

</body>

</html>
