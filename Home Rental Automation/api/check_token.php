<?php 
// Function to verify token and fetch data based on the role
function checkToken($userID, $role) {
    global $pdo;
    // Determine table based on role
    $table = ($role === 'owner') ? 'owners' : 'tenants';
    
    // Fetch the token from the respective table
    $sql = "SELECT token FROM $table WHERE id = :userID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    
    // Check if a user with the provided ID exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $token = $user['token'];

        // Check if the token exists and is valid
        if ($token) {
            // Return the token if valid
            echo json_encode(['status' => true, 'token' => $token]);
        } else {
            // No token found
            echo json_encode(['status' => false, 'message' => 'Token not found or expired.']);
        }
    } else {
        // No user found with that ID
        echo json_encode(['status' => false, 'message' => 'User not found.']);
    }
}
