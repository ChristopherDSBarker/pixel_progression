<?php
header('Content-Type: application/json');

$jsonPayload = file_get_contents('php://input');
$data = json_decode($jsonPayload, true);

if (!$data || !isset($data['playerId']) || !isset($data['timeSeconds'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid score payload']);
    exit;
}

$safePlayerId = preg_replace("/[^a-zA-Z0-9]+/", "", $data['playerId']);
$timeSeconds = floatval($data['timeSeconds']);
$timeFormatted = isset($data['timeFormatted']) ? preg_replace("/[^0-9:.]+/", "", $data['timeFormatted']) : "";

if ($safePlayerId === "" || $timeSeconds < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid score values']);
    exit;
}

if (!file_exists('scores')) {
    mkdir('scores', 0777, true);
}

$scoreEntry = [
    'playerId' => $safePlayerId,
    'timeSeconds' => $timeSeconds,
    'timeFormatted' => $timeFormatted,
    'candies' => isset($data['candies']) ? intval($data['candies']) : 0,
    'health' => isset($data['health']) ? intval($data['health']) : 0,
    'level' => isset($data['level']) ? intval($data['level']) : 5,
    'completedAt' => date('c')
];

$scoreLog = 'scores/scores.jsonl';
$bestScore = 'scores/best_' . $safePlayerId . '.json';

$saved = file_put_contents($scoreLog, json_encode($scoreEntry) . PHP_EOL, FILE_APPEND | LOCK_EX);

if ($saved === false) {
    echo json_encode(['status' => 'error', 'message' => 'Could not save score']);
    exit;
}

$currentBest = null;

if (file_exists($bestScore)) {
    $currentBest = json_decode(file_get_contents($bestScore), true);
}

if (!$currentBest || !isset($currentBest['timeSeconds']) || $timeSeconds < floatval($currentBest['timeSeconds'])) {
    file_put_contents($bestScore, json_encode($scoreEntry, JSON_PRETTY_PRINT), LOCK_EX);
}

echo json_encode([
    'status' => 'success',
    'timeSeconds' => $timeSeconds,
    'timeFormatted' => $timeFormatted
]);
?>
