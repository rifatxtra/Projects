<?php
// Function to get payment methods by owner ID
function getPaymentMethod($id) {
    global $pdo;
    $query = "SELECT * FROM payment_method WHERE owner_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if data is found
    if ($stmt->rowCount() > 0) {
        // Return success response with data
        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    } else {
        // Return failure response if no data found
        echo json_encode([
            'status' => false,
            'message' => 'No payment methods found.'
        ]);
    }
}

// Function to update payment methods for a specific owner ID
function updatePaymentmethod($id) {
    global $pdo;

    // Get the raw POST body
    $jsonData = file_get_contents('php://input');
    
    // Decode the JSON data into an associative array
    $data = json_decode($jsonData, true);

    // Debugging: Log the incoming data for debugging
    error_log('Received Data: ' . print_r($data, true));

    // Check if the data is valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['status' => false, 'message' => 'Invalid JSON data.'];
    }

    // Loop through the payment methods (bkash, nagad, rocket) to update each one
    foreach ($data as $method => $methodData) {
        // Debugging: Log each payment method being processed
        error_log('Processing Method: ' . $method);

        // Check if the payment method exists for the owner
        $query = "SELECT * FROM payment_method WHERE owner_id = :id AND payment_method = :method";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':method', $method, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // If method exists, update the record
            $updateQuery = "UPDATE payment_method 
                            SET account_type = :account_type, number = :number 
                            WHERE owner_id = :id AND payment_method = :method";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                ':account_type' => $methodData['numberType'],
                ':number' => $methodData['number'],
                ':id' => $id,
                ':method' => $method,
            ]);
        } else {
            // If method doesn't exist, insert it
            $insertQuery = "INSERT INTO payment_method (owner_id, payment_method, account_type, number) 
                            VALUES (:id, :method, :account_type, :number)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([
                ':id' => $id,
                ':method' => $method,
                ':account_type' => $methodData['numberType'],
                ':number' => $methodData['number'],
            ]);
        }
    }

    // Return success message after processing all methods
    echo json_encode(['status' => true, 'message' => 'Payment methods updated successfully.']);
}

