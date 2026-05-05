<?php
session_start();
header('Content-Type: application/json');

$jsonPayload = file_get_contents('php://input');
$data = json_decode($jsonPayload, true);

if (!$data || !isset($data['playerId']) || !isset($data['timeSeconds'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid score payload']);
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : $data['playerId'];
$safePlayerId = trim(preg_replace("/[^a-zA-Z0-9_ -]+/", "", $username));
$timeSeconds = floatval($data['timeSeconds']);

if ($safePlayerId === "" || $timeSeconds < 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid score values']);
    exit;
}

function formatScoreTime($seconds) {
    $safeSeconds = max(0, $seconds);
    $minutes = floor($safeSeconds / 60);
    $wholeSeconds = floor($safeSeconds % 60);
    $hundredths = floor(($safeSeconds - floor($safeSeconds)) * 100);

    return sprintf("%d:%02d.%02d", $minutes, $wholeSeconds, $hundredths);
}

$timeFormatted = isset($data['timeFormatted']) ? preg_replace("/[^0-9:.]+/", "", $data['timeFormatted']) : formatScoreTime($timeSeconds);
$completedAt = date('c');

$db = new SQLite3(__DIR__ . '/game.db');

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

$saveStmt = $db->prepare("
    INSERT INTO best_times (username, time_seconds, time_formatted, completed_at)
    VALUES (:username, :time_seconds, :time_formatted, :completed_at)
    ON CONFLICT(username) DO UPDATE SET
        time_seconds = excluded.time_seconds,
        time_formatted = excluded.time_formatted,
        completed_at = excluded.completed_at
    WHERE excluded.time_seconds < best_times.time_seconds
");

$saveStmt->bindValue(':username', $safePlayerId, SQLITE3_TEXT);
$saveStmt->bindValue(':time_seconds', $timeSeconds, SQLITE3_FLOAT);
$saveStmt->bindValue(':time_formatted', $timeFormatted, SQLITE3_TEXT);
$saveStmt->bindValue(':completed_at', $completedAt, SQLITE3_TEXT);

$saved = $saveStmt->execute();

if (!$saved) {
    echo json_encode(['status' => 'error', 'message' => 'Could not save score']);
    exit;
}

$isNewBest = intval($db->querySingle("SELECT changes()")) > 0;

$rankStmt = $db->prepare("
    SELECT COUNT(*) + 1 AS rank
    FROM best_times
    WHERE time_seconds < (
        SELECT time_seconds
        FROM best_times
        WHERE username = :username
    )
");
$rankStmt->bindValue(':username', $safePlayerId, SQLITE3_TEXT);
$rankResult = $rankStmt->execute();
$rankRow = $rankResult ? $rankResult->fetchArray(SQLITE3_ASSOC) : ['rank' => null];

$bestStmt = $db->prepare("
    SELECT time_seconds, time_formatted
    FROM best_times
    WHERE username = :username
");
$bestStmt->bindValue(':username', $safePlayerId, SQLITE3_TEXT);
$bestResult = $bestStmt->execute();
$bestRow = $bestResult ? $bestResult->fetchArray(SQLITE3_ASSOC) : false;

echo json_encode([
    'status' => 'success',
    'submittedTimeSeconds' => $timeSeconds,
    'submittedTimeFormatted' => $timeFormatted,
    'bestTimeSeconds' => $bestRow ? floatval($bestRow['time_seconds']) : $timeSeconds,
    'bestTimeFormatted' => $bestRow ? $bestRow['time_formatted'] : $timeFormatted,
    'newBest' => $isNewBest,
    'rank' => $rankRow ? $rankRow['rank'] : null
]);
?>
