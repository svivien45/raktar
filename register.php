<?php

require_once 'database.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$database = new Store();
$database->createTables();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = htmlentities($_POST['password']);
    $passwordRepeat = $_POST['password_repeat'];

    if ($password !== $passwordRepeat) {
        echo "Passwords do not match.";
    } else {
        if ($database->getUserByEmail($email)) {
            echo "User with this email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $token = bin2hex(random_bytes(50));
            $token_valid_until = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $database->registerUser($name, $email, $hashedPassword, $token, $token_valid_until);

            sendActivationEmail($email, $name, $token, $token_valid_until);

            echo "Registration successful. You can now log in.";
        }
    }
}

function sendActivationEmail($email, $name, $token, $expiration_time) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->SMTPAuth = false;
        $mail->Port = 1025;

        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Complete Your Registration';
        $activation_link = "http://localhost:85/raktar/activate.php?token=" . $token;
        $mail->Body = "Dear $name,<br><br>Please click the link below to complete your registration:<br><a href='$activation_link'>Register activation</a><br><br>The link will expire on $expiration_time<br><br>Best regards,<br>Your Company";
        $mail->AltBody = "Dear $name,\n\nPlease click the link below to complete your registration:\n$activation_link\n\nThe link will expire on $expiration_time\n\nBest regards,\nSavanyú Vivien";
        $mail->send();
        echo 'Activation email sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="register.php">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="password_repeat">Repeat Password:</label><br>
        <input type="password" id="password_repeat" name="password_repeat" required><br>
        <input type="submit" value="Register">
    </form>


    <form method='post' action='index.php' class='btn'>
        <button type='submit'>Home</button>
    </form><br>

</html>
