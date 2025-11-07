<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendVerificationMail($toAddress, $toName, $tourl) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io'; // o live.smtp.mailtrap.io per stream reali
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['EMAIL_USER']; // username fornito da Mailtrap
        $mail->Password   = $_ENV['EMAIL_PASSWORD']; // password fornita da Mailtrap
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // o 'ssl' su porta 465
        $mail->Port       = 587; // 25, 465, 587 o 2525 sono possibili

        // Mittente e destinatario
        $mail->setFrom('registrazione_xample@noreply.com', 'Registrazione Xample Website');
        $mail->addAddress($toAddress, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Body = "Hi ". $toName ."\nCode for the verification : " . $tourl;
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}