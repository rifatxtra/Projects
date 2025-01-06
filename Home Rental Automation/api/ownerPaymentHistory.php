<?php
function fetchpaymentHistory($id){
    global $pdo;
    $sql="select * from payments join tenants on tenants.id=payments.tenant_id where payments.owner_id=:id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(":id",$id, PDO::PARAM_INT);
    $stmt->execute();
    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if($stmt->rowCount()>0){
        echo json_encode([
            'status'=>true,
            'data'=>$data
        ]);
    }
    else{
        echo json_encode([
            'status'=>false,
            'msg'=>'No Payment History Found'
        ]);
    }
}