<?php
require_once 'database.php';
$database = new Store();

if (isset($_SESSION['user_id'])) {
    $user = $database->getUserById($_SESSION['user_id']);
    if ($user && $user['is_active'] == 1) {
        $database->setUserInactive($user['id']);
    }
}

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
header("Location: index.php");
exit();
?>
