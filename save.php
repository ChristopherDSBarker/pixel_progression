<?php
header('Content-Type: application/json');

// Capture raw payload from Fetch API
$jsonPayload = file_get_contents('php://input');
$data = json_decode($jsonPayload, true);

if (!$data ||!isset($data['playerId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data payload']);
    exit;
}

// Sanitize player ID to prevent Path Traversal vulnerabilities
$safePlayerId = preg_replace("/[^a-zA-Z0-9]+/", "", $data['playerId']);
$filename = 'saves/player_'. $safePlayerId. '.json';

// Ensure the directory exists
if (!file_exists('saves')) {
    mkdir('saves', 0777, true);
}

// Save securely using an exclusive lock
if (file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> a3d0d9e5aa49ad4bb8f98b513c6d11e5eb4025b6
