<?php
session_start();
header('Content-Type: application/json');

// Capture raw payload from Fetch API
$jsonPayload = file_get_contents('php://input');
$data = json_decode($jsonPayload, true);

if (!$data || !isset($data['playerId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data payload']);
    exit;
}

$username = $_SESSION['username'];
$health = $data['health'];
$candies = $data['candies'];
$jumpheight = $data['jumpHeight'];
$speed = $data['speed'];
$level = $data['level'];
$seconds = $data['seconds'];

$username = $_SESSION['username'];
$statsdb = new SQLite3("login.db");
$query = "SELECT * FROM STATS WHERE L_USERNAME = '$username'";
$success = $statsdb->query($query);

$row = $success->fetchArray(SQLITE3_ASSOC);
if ($row) {
    $update = "UPDATE STATS SET S_LEVEL = '$level', S_JUMPHEIGHT = '$jumpheight', S_SPEED = '$speed',
        S_HEALTH = '$health', S_CANDIES = '$candies', S_SECONDS = '$seconds' WHERE L_USERNAME = '$username'";
    $statsdb->exec($update);
} else {
    $save = "INSERT INTO STATS (L_USERNAME, S_LEVEL, S_JUMPHEIGHT, S_SPEED, S_HEALTH, S_CANDIES, S_SECONDS)
        VALUES ('$username', '$level', '$jumpheight', '$speed', '$health', '$candies', '$seconds')";
    $statsdb->exec($save);
}
echo json_encode(['status' => 'success']);
exit;
?>