<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'pdf.php';

$mail = new PHPMailer(true);

$fileName = 'lowStock.pdf';
$pdf = new PDF();
$pdf->createPDF('F', 'lowStock.pdf');

try {
    $mail->isSMTP();
    $mail->Host       = 'localhost';
    $mail->SMTPAuth   = false;
    //$mail->Username   = 'user@example.com';
    //$mail->Password   = 'secret';
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 1025;

    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');
    $mail->addAddress('ellen@example.com');
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    $mail->addAttachment('lowStock.pdf');
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

    $mail->isHTML(true);
    $mail->Subject = 'LowStock';
    $mail->Body    = 'A kifogyóban lévő termékek a mellékelt fájlban található.';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

