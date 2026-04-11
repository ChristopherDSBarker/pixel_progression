<?php
$db = new SQLite3(__DIR__ . '/game.db');

$db->exec("
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)");

echo "TABLE CREATED";
?>