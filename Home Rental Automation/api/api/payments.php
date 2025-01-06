<?php

function getRecentTransactions($tenantId,$limit) {
    global $pdo;

    try {
        if($limit!=null)
        {   
            
            $stmt = $pdo->prepare("SELECT * FROM payments WHERE tenant_id = :tenant_id ORDER BY date DESC LIMIT :limit");
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        } 
        else $stmt = $pdo->prepare("SELECT * FROM payments WHERE tenant_id = :tenant_id ORDER BY date DESC");
        $stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => true, 'data' => $transactions]);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'error' => $e->getMessage()]);
    }
}


