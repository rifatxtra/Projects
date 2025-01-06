<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Get tenants by id
function getTenants($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => true, 'data' => $tenants]);
}

function getAllTenant(){
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM tenants");
    $stmt->execute();
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => true, 'data' => $tenants]);
}

// Remove tenant by ID
function removeTenant($tenantId) {
    global $pdo;
    $sql = "DELETE FROM tenants WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $tenantId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => true,
            'msg' => 'Delete operation successful'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Delete operation failed'
        ]);
    }
}

// Generate a random password
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    return substr(str_shuffle($chars), 0, $length);
}

// Hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
function sendMail($subject,$body,$email,$name){
    $mail = new PHPMailer(true);

try {
    
    // Server settings
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host = 'rifatxtra.xyz';                         // SMTP server
    $mail->SMTPAuth = true;                                     // Enable SMTP authentication
    $mail->Username = 'test@rifatxtra.xyz';                     // SMTP username
    $mail->Password = 'test@rifatxtra.xyz';                   // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Use SSL encryption
    $mail->Port = 465;                                          // TCP port for SSL

    // Recipients
    $mail->setFrom('test@rifatxtra.xyz', 'Owner');              // From address
    $mail->addAddress($email, $name); // Add recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body= $body;


    // Send email
    $mail->send();
    echo json_encode(['status'=>true,'msg'=>'Message has been sent']);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}

// Add new tenant
function addTenant($name, $email, $flatId = null) {
    global $pdo;
    // Generate and hash the password
    $generatedPassword = generatePassword();
    $hashedPassword = hashPassword($generatedPassword);

    $sql = "INSERT INTO tenants (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashedPassword);
    
    if ($stmt->execute()) {
        $subject="Welcome $name";
        $body="<p>Your account has been added, Use them to log in and change your password</p><br><p>Email: $email</p><br><p>Password: $generatedPassword</p>";
        //sendMail($subject,$body,$email,$name);
        $tenantId = $pdo->lastInsertId();
        if ($flatId) {
            addTenantToFlat($flatId, $tenantId);
        } else {
            echo json_encode([
                'status' => true,
                'msg' => 'New tenant added without flat assignment',
                'name'=>$name,
                'email'=>$email,
                'subject'=>$subject,'body'=>$body
            ]);
        }
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Tenant add failed'
        ]);
    }
}

// Free a tenant's flat (set tenants to null)
function freeTenant($flatId) {
    global $pdo;
    $sql = "UPDATE flats SET tenants = NULL WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $flatId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => true,
            'msg' => 'Flat is now free'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Failed to free flat'
        ]);
    }
}

function addTenantToFlat($flatId, $tenantId) {
    global $pdo;
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // First, free all flats associated with this tenant
        $sql = "UPDATE flats SET tenants = NULL WHERE tenants = :tenantId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":tenantId", $tenantId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Then, assign the tenant to the new flat
        $sql = "UPDATE flats SET tenants = :tenantId WHERE id = :flatId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":tenantId", $tenantId, PDO::PARAM_INT);
        $stmt->bindParam(":flatId", $flatId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Commit the transaction
        $pdo->commit();
        
        echo json_encode([
            'status' => true,
            'msg' => 'Tenant successfully added to flat'
        ]);
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollback();
        echo json_encode([
            'status' => false,
            'msg' => 'Failed to add tenant to flat: ' . $e->getMessage()
        ]);
    }
}