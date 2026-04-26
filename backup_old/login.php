<?php
session_start();

$db = new SQLite3(__DIR__ . '/game.db');

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $db->prepare("SELECT * FROM users WHERE username = :u");
$stmt->bindValue(':u', $username);

$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user['username'];
    header("Location: home.php");
    exit();
} else {
    echo "Invalid login. <a href='login.html'>Try again</a>";
}
?>