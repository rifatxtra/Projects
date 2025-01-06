<?php
function getPaymentMethods() { 
    global $pdo;
    try { $stmt = $pdo->query("SELECT * FROM payment_method");
        $paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => true, 'data' => $paymentMethods]); 
    } catch (Exception $e) { 
        echo json_encode(['status' => false, 'error' => $e->getMessage()]); 
    }
}