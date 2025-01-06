<?php
function fetchPaymentStat() {
    global $pdo;
    
    try {
        $query = "SELECT * FROM payments";
        $result = $pdo->query($query);
        
        if (!$result) {
            throw new Exception("Failed to execute query.");
        }

        $structuredData = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tenantId = $row['tenant_id'];
            $amount = $row['amount'];
            $monthYear = $row['month_year'];

            if (!isset($structuredData[$tenantId])) {
                $structuredData[$tenantId] = [
                    'totalEarnings' => 0,
                    'earnings' => []
                ];
            }

            $structuredData[$tenantId]['totalEarnings'] += $amount;
            $structuredData[$tenantId]['earnings'][] = [
                'amount' => $amount,
                'month_year' => $monthYear,
                'method' => $row['method']
            ];
        }

        echo json_encode($structuredData);

    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
    }
}
