<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Catering Services</title>
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
            text-align: center;
        }

        /* Introduction Section */
        .intro {
            margin-top: 50px;
        }

        .intro h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .intro p {
            font-size: 18px;
        }

        /* Features Section */
        .features {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .feature {
            width: 30%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
        }

        .feature h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 16px;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div>
            <h1>Company Catering Services</h1>
        </div>
        <div>
            <a style="font-size: 26px;" href="#">Home</a>
            <a style="font-size: 26px;" href="users/login.php">Log In</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Introduction Section -->
        <div class="intro">
            <h1>Welcome to Our Company Catering Services</h1>
            <p>Enjoy delicious and nutritious meals provided by our catering service. We offer a variety of set menus for employees to choose from.</p>
        </div>

        <!-- Features Section -->
        <div class="features">
            <div class="feature">
                <h2>Quality Ingredients</h2>
                <p>We use only the freshest and highest quality ingredients to prepare our meals.</p>
            </div>
            <div class="feature">
                <h2>Set Menus</h2>
                <p>Employees can choose from a selection of set menus, with different options available each day.</p>
            </div>
            <div class="feature">
                <h2>Convenient Ordering</h2>
                <p>Ordering is quick and easy. Employees can order their meal with just a few clicks.</p>
            </div>
        </div>
    </div>

</body>

</html>
