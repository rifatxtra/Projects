<?php
function login($email, $password,$route)
{
    global $pdo;

    // Prepare and execute query to fetch user by email
    $sql = "SELECT * FROM $route WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($user) {
        // Check if the password matches
        if (password_verify($password, $user['password'])) {
            // Check if the token exists and if it is expired (older than 30 minutes)
            $tokenCreationTime = strtotime($user['token_created_at']);
            $currentTime = time();

            if ($user['token'] && ($currentTime - $tokenCreationTime) > 1800) { // 1800 seconds = 30 minutes
                // Token expired, clear it
                $sql = "UPDATE $route SET token = NULL, token_created_at = NULL WHERE email = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                echo "Token expired. Please login again.";
                return;
            }

            // Generate a new token
            $token = bin2hex(random_bytes(16)); // Random token generation

            // Save the token and timestamp in the database
            $tokenCreatedAt = date('Y-m-d H:i:s'); // Current timestamp

            // Update the user's token and timestamp
            $sql = "UPDATE $route SET token = :token, token_created_at = :token_created_at WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':token_created_at', $tokenCreatedAt);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Return the generated token to the frontend (for example, you can send it as a JSON response)
            echo json_encode(["status"=>true, "token" => $token,'role'=>$user['role'], "id"=>$user['id'], "message" => "Login successful"]);
        } else {
            echo json_encode(["status"=>false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status"=>false, "message" => "User not found."]);
    }
}
