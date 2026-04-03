<?php
$data = json_decode(file_get_contents("php://input"), true);

$db = new SQLite3("game.db");

// create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS player (
    id INTEGER PRIMARY KEY,
    health INTEGER,
    speed INTEGER,
    jump INTEGER
)");

// clear old data
$db->exec("DELETE FROM player");

// insert new data
$stmt = $db->prepare("INSERT INTO player (health, speed, jump) VALUES (?, ?, ?)");
$stmt->bindValue(1, $data["health"]);
$stmt->bindValue(2, $data["speed"]);
$stmt->bindValue(3, $data["jump"]);
$stmt->execute();

echo "Saved!";
?>
