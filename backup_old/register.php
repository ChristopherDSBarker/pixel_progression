<?php
$db = new SQLite3(__DIR__ . '/game.db');

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
$stmt->bindValue(':u', $username);
$stmt->bindValue(':p', $password);

if ($stmt->execute()) {
    echo "Account created. <a href='login.html'>Login</a>";
} else {
    echo "Username exists.";
}
?>