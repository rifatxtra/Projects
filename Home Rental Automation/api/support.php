<?php 
function storeSupport($id, $data) {
    global $pdo;

    // Correct the query to select from the correct table
    $query = "SELECT * FROM tenants WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $tenant = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll for a single result

    if ($tenant) {
        $ownerid = $tenant['owner_id'];
        $st = $pdo->prepare("SELECT * FROM owners WHERE id = :id");
        $st->bindParam(":id", $ownerid, PDO::PARAM_INT);
        $st->execute();
        $owner = $st->fetch(PDO::FETCH_ASSOC);
        $owneremail = $owner['email'];

        $tenantemail = $tenant['email'];
        if ($ownerid) {
            $query = $data['query'];
            $tenantid = $data['tenant_id'];
            
            // Prepare and execute the insert statement
            $stmt = $pdo->prepare("INSERT INTO supports (query, tenant_id, owner_id) VALUES (:query, :tenant_id, :owner_id)");
            $stmt->bindParam(':query', $query);
            $stmt->bindParam(':tenant_id', $tenantid, PDO::PARAM_INT);
            $stmt->bindParam(':owner_id', $ownerid, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Return success status as JSON
                echo json_encode(['status' => true, 'data' => [
                    'owneremail' => $owneremail,
                    'tenantemail' => $tenantemail
                ]]);
            } else {
                // Return failure status as JSON
                echo json_encode(['status' => false, 'error' => 'Failed to insert data']);
            }
        } else {
            // Return failure status as JSON if owner_id is not found
            echo json_encode(['status' => false, 'error' => 'Owner ID not found']);
        }
    } else {
        // Return failure status as JSON if tenant is not found
        echo json_encode(['status' => false, 'error' => 'Tenant not found']);
    }
}

function getSupport($tenantId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM supports WHERE tenant_id = :tenant_id ORDER BY id DESC");
        $stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
        $stmt->execute();
        $supports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => true, 'data' => $supports]);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}


