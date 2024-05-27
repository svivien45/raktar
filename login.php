<?php
session_start();
require_once 'database.php';
$database = new Store();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = htmlentities($_POST['password']);

        $user = $database->getUserByEmail($email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($user && $user['password'] == $hashedPassword && $email == $user['email']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            $database->setUserActive($user['id']);

            header("Location: index.php");
            exit();
        } else {
            echo "Wrong username or password.";
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

    <form method='post' action='index.php' class='btn'>
        <button type='submit'>Home</button>
    </form><br>
</body>
</html>
