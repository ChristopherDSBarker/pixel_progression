<?php
session_start();

function ensureBestTimesTable($db) {
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
}

$db = new SQLite3(__DIR__ . '/game.db');
ensureBestTimesTable($db);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$safeUsername = $username ? trim(preg_replace("/[^a-zA-Z0-9_ -]+/", "", $username)) : null;
$best = false;
$rank = null;

if ($safeUsername) {
    $stmt = $db->prepare("
        SELECT username, time_seconds, time_formatted, completed_at
        FROM best_times
        WHERE username = :username
    ");
    $stmt->bindValue(':username', $safeUsername, SQLITE3_TEXT);
    $result = $stmt->execute();
    $best = $result ? $result->fetchArray(SQLITE3_ASSOC) : false;

    if ($best) {
        $rankStmt = $db->prepare("
            SELECT COUNT(*) + 1 AS rank
            FROM best_times
            WHERE time_seconds < :time_seconds
        ");
        $rankStmt->bindValue(':time_seconds', $best['time_seconds'], SQLITE3_FLOAT);
        $rankResult = $rankStmt->execute();
        $rankRow = $rankResult ? $rankResult->fetchArray(SQLITE3_ASSOC) : false;
        $rank = $rankRow ? $rankRow['rank'] : null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Best Time</title>
    <link rel="icon" type="image/png" href="conceptlogo.png">
    <link rel="stylesheet" href="style_home.css?v=1">
    <style>
        body {
            background-color: teal;
            font-family: monospace;
        }

        .score-card {
            width: min(640px, 90vw);
            margin: 30px auto;
            background-color: aquamarine;
            border: 5px double black;
            padding: 20px;
            text-align: center;
        }

        .time {
            display: block;
            font-size: 48px;
            margin: 16px 0;
            color: black;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .actions .button {
            display: inline-block;
            width: 260px;
        }
    </style>
</head>
<body>
    <div style="background-color: aquamarine; border: 5px black double; padding: 5px">
        <h1 id="title1">P i x e l</h1>
        <h1 id="title2"> P r o g r e s s i o n </h1>
    </div>

    <main class="score-card">
        <h1>My Best Time</h1>

        <?php if (!$username) { ?>
            <p>Log in to save and view your best boss clear time.</p>
        <?php } else if ($best) { ?>
            <p><?php echo htmlspecialchars($username); ?></p>
            <span class="time"><?php echo htmlspecialchars($best['time_formatted']); ?></span>
            <p>Rank: <?php echo htmlspecialchars($rank); ?></p>
            <p>Completed: <?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($best['completed_at']))); ?></p>
        <?php } else { ?>
            <p>No boss clear saved yet.</p>
        <?php } ?>

        <div class="actions">
            <a href="home.php" class="button">HOME</a>
            <a href="leaderboard.php" class="button">LEADERBOARD</a>
            <?php if ($username) { ?>
                <a href="index.php" class="button">PLAY</a>
            <?php } else { ?>
                <a href="login.php" class="button">LOG IN</a>
            <?php } ?>
        </div>
    </main>
</body>
</html>
