<?php 

function getNumber() {
    global $pdo;
    $v = 0;
    $query = "SELECT COUNT(id) as count FROM pending_payments WHERE status = :st";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":st", $v, PDO::PARAM_INT);
    $stmt->execute();
    $num = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true, 
        'number' => $num['count']
    ]);
}

function getPayments(){
    global $pdo;
    $v=0;
    $query = "SELECT tenants.name as name, pending_payments.id as id, pending_payments.transaction_id as txid, pending_payments.payment_method as pm, pending_payments.tenant_id as tid, pending_payments.created_at as created, pending_payments.amount as amount, pending_payments.property_id as pid, pending_payments.flat_name as fname FROM pending_payments inner join tenants on tenants.id=pending_payments.tenant_id WHERE pending_payments.status = :st";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":st", $v, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true, 
        'data' => $data
    ]);
}


function approvePayment($paymentId) {
    global $pdo;

    // Begin a transaction
    $pdo->beginTransaction();

    try {
        // Fetch payment details from pending_payments
        $stmt = $pdo->prepare("SELECT * FROM pending_payments WHERE id = :id");
        $stmt->bindParam(':id', $paymentId, PDO::PARAM_INT);
        $stmt->execute();
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            // Extract month and year from the current date
            $monthYear = date('Y-m');

            // Insert payment details into the new table
            $insertStmt = $pdo->prepare("
                INSERT INTO payments (tenant_id, amount, date, method, created_at, updated_at, owner_id, month_year, property_id, flat_name)
                VALUES (:tenant_id, :amount, CURDATE(), :method, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, :owner_id, :month_year, :property_id, :flat_name)
            ");
            $ownerid=1;
            $insertStmt->bindParam(':tenant_id', $payment['tenant_id'], PDO::PARAM_INT);
            $insertStmt->bindParam(':amount', $payment['amount']);
            $insertStmt->bindParam(':method', $payment['payment_method'], PDO::PARAM_STR);
            $insertStmt->bindParam(':owner_id', $ownerid, PDO::PARAM_INT);
            $insertStmt->bindParam(':month_year', $monthYear, PDO::PARAM_STR);
            $insertStmt->bindParam(':property_id', $payment['property_id'], PDO::PARAM_INT);
            $insertStmt->bindParam(':flat_name', $payment['flat_name'], PDO::PARAM_STR);

            $insertStmt->execute();

            // Update status of the pending payment
            $updateStmt = $pdo->prepare("UPDATE pending_payments SET status = 1 WHERE id = :id");
            $updateStmt->bindParam(':id', $paymentId, PDO::PARAM_INT);
            $updateStmt->execute();

            // Commit the transaction
            $pdo->commit();

            echo json_encode(['status' => true, 'message' => 'Payment approved and inserted successfully']);
        } else {
            // Rollback the transaction if payment details are not found
            $pdo->rollBack();
            echo json_encode(['status' => false, 'message' => 'Payment not found']);
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}


function rejectPayment($id) {
    global $pdo;
    $v = 2;
    $query = "UPDATE pending_payments SET status = :st WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":st", $v, PDO::PARAM_INT);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => true]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to update the payment status.']);
    }
}

