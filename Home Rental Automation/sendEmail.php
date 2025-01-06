<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Autoload PHPMailer

$mail = new PHPMailer(true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
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
    $mail->addAddress($data['email'], $data['name']); // Add recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = $data['subject'];
    $mail->Body= $data['body'];


    // Send email
    $mail->send();
    echo json_encode(['status'=>true,'msg'=>'Message has been sent']);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
