<?php //init save and login info databases, run once to create tables, can be deleted after
$db = new SQLite3(__DIR__ . '/login.db');

$db->exec("
CREATE TABLE LOGININFO (
    L_USERNAME TEXT PRIMARY KEY,
    L_PASSWORD TEXT
)");

$db->exec("
CREATE TABLE STATS (
    L_USERNAME TEXT PRIMARY KEY,
    S_LEVEL,
    S_JUMPHEIGHT,
    S_SPEED,
    S_HEALTH,
    S_CANDIES,
    S_SECONDS,
    FOREIGN KEY (L_USERNAME) REFERENCES LOGININFO(L_USERNAME)
);");

$db = new SQLite3(__DIR__ . '/game.db');

$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)");

$db->exec("
CREATE TABLE IF NOT EXISTS best_times (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    time_seconds REAL NOT NULL,
    time_formatted TEXT NOT NULL,
    completed_at TEXT NOT NULL
)");

$db->exec("
CREATE INDEX IF NOT EXISTS idx_best_times_time
ON best_times (time_seconds)
");

echo "TABLES READY";
?>
