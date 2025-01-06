<?php

// Function to fetch pending payment data
function getPendingPayments() {
    global $pdo;
    $sql = "
        SELECT t.id AS tenant_id, t.name AS tenant_name,t.email as email, t.rent_amount, t.rent_start_date, 
               f.flat_name, f.rent AS flat_rent, 
               SUM(p.amount) AS total_paid_amount
        FROM tenants t
        LEFT JOIN flats f ON t.flat_id = f.id
        LEFT JOIN payments p ON t.id = p.tenant_id
        WHERE t.rent_start_date IS NOT NULL
        GROUP BY t.id, f.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tenantData = [];

    foreach ($tenants as $tenant) {
        $tenantId = $tenant['tenant_id'];
        $tenantName = $tenant['tenant_name'];
        $flatName = $tenant['flat_name'];
        $flatRent = $tenant['flat_rent'];
        $email=$tenant['email'];
        $rentStartDate = $tenant['rent_start_date'];
        $totalPaidAmount = $tenant['total_paid_amount'] ?? 0; // Sum of all payments made by the tenant

        // Calculate months pending
        $startDate = new DateTime($rentStartDate);
        $currentDate = new DateTime();
        $interval = $startDate->diff($currentDate);
        $monthsPending = $interval->m + ($interval->y * 12);

        // Calculate total pending amount
        $totalRentExpected = $monthsPending * $flatRent;
        $pendingAmount = $totalRentExpected - $totalPaidAmount;

        // Only add tenants with pending amounts greater than zero
        if ($pendingAmount > 0) {
            $tenantData[] = [
                'tenant_name' => $tenantName,
                'flat_name' => $flatName,
                'months_pending' => $monthsPending,
                'total_pending_amount' => $pendingAmount,
                'tenantID'=>$tenantId,
                'email'=>$email
            ];
        }
    }

    echo json_encode($tenantData);
}
//fetch paid but not confirmed
function getPendingPaymentsforPending($tenantId){

    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM pending_payments WHERE tenant_id = :tenant_id ORDER BY created_at DESC");
        $stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
        $stmt->execute();
        $pendingPayments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => true, 'data' => $pendingPayments]);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}


function storePendingPayment($data)
{
    global $pdo;

    $id = $data['tenant_id'];

    // Fetch flat, property, and rent details
    $flatQuery = $pdo->prepare("SELECT flat_name, property_id, rent FROM flats WHERE tenants = :id");
    $flatQuery->bindParam(":id", $id, PDO::PARAM_INT);
    $flatQuery->execute();
    $flatResult = $flatQuery->fetch(PDO::FETCH_ASSOC);

    if (!$flatResult) {
        echo json_encode(['status' => false, 'error' => 'No flat or property found for the given tenant']);
        return;
    }

    $flatName = $flatResult['flat_name'];
    $propertyID = $flatResult['property_id'];
    $amount = $flatResult['rent']; // Correctly fetch the rent value

    try {
        // Insert pending payment
        $stmt = $pdo->prepare(
            "INSERT INTO pending_payments 
            (transaction_id, payment_method, tenant_id, status, flat_name, property_id, amount) 
            VALUES (:transaction_id, :payment_method, :tenant_id, 0, :flatname, :propertyid, :amount)"
        );
        $stmt->bindParam(':transaction_id', $data['transaction_id'], PDO::PARAM_STR);
        $stmt->bindParam(':payment_method', $data['payment_method'], PDO::PARAM_STR);
        $stmt->bindParam(':tenant_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(":flatname", $flatName, PDO::PARAM_STR);
        $stmt->bindParam(":propertyid", $propertyID, PDO::PARAM_INT);
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);

        $stmt->execute();

        $pendingPayment = [
            'id' => $pdo->lastInsertId(),
            'transaction_id' => $data['transaction_id'],
            'payment_method' => $data['payment_method'],
            'tenant_id' => $data['tenant_id'],
            'status' => 0,
        ];

        echo json_encode(['status' => true, 'data' => $pendingPayment, 'message' => 'Pending payment stored successfully']);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}

