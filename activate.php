<?php
require_once 'database.php';
$database = new Store();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = $database->getUserByToken($token);

    if ($user) {
        $email = $user['email'];
        $expires_at = $user['token_valid_until'];

        if (strtotime($expires_at) > time()) {
            $database->activateUser($email);
            $database->deleteToken($token);
            echo "Dear " . htmlspecialchars($user['name']) . ", your registration is active.";
        } else {
            echo "The activation link has expired.";
        }
    } else {
        echo "Invalid activation link.";
    }
} else {
    echo "No token provided.";
}
?>
