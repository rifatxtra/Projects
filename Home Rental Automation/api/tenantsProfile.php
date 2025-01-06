<?php

// Function to fetch profile
function getProfile($id) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tenant) {
            echo json_encode(['status' => true, 'data' => $tenant]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Tenant not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}

// Function to update profile
function updateProfile($id, $data) {
    global $pdo;

    // Validate inputs
    $errors = [];

    if (!preg_match('/^0\d{10}$/', $data['contact'])) {
        $errors['contact'] = 'Contact number must be 11 digits and start with 0.';
    }

    if (!preg_match('/^\d{13}$|^\d{11}$/', $data['nid'])) {
        $errors['nid'] = 'NID number must be either 11 or 13 digits.';
    }

    if (!empty($errors)) {
        echo json_encode(['status' => false, 'errors' => $errors]);
        return;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE tenants 
            SET 
                name = :name, 
                email = :email, 
                contact = :contact, 
                nid = :nid, 
                address = :address 
            WHERE id = :id
        ");

        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':contact', $data['contact'], PDO::PARAM_STR);
        $stmt->bindParam(':nid', $data['nid'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update profile']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}