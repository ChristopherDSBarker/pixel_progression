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

$results = $db->query("
    SELECT username, time_formatted, time_seconds, completed_at
    FROM best_times
    ORDER BY time_seconds ASC, completed_at ASC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <link rel="icon" type="image/png" href="conceptlogo.png">
    <link rel="stylesheet" href="style_home.css?v=1">
    <style>
        body {
            background-color: teal;
            font-family: monospace;
        }

        .board {
            width: min(760px, 90vw);
            margin: 30px auto;
            background-color: aquamarine;
            border: 5px double black;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgb(255, 255, 135);
        }

        th,
        td {
            border: 2px dashed black;
            padding: 10px;
            text-align: left;
            font-size: 18px;
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

    <audio id="sfx_hover" src="assets/sfx_btn_hover.mp3" preload="auto"></audio>
    
    <div style="background-color: aquamarine; border: 5px black double; padding: 5px">
        <h1 id="title1">P i x e l</h1>
        <h1 id="title2"> P r o g r e s s i o n </h1>
    </div>

    <main class="board">
        <h1>Top 5 Best Times</h1>

        <table>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Best Time</th>
                <th>Completed</th>
            </tr>

            <?php
            $rank = 1;
            $hasRows = false;

            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $hasRows = true;
                ?>
                <tr>
                    <td><?php echo $rank; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['time_formatted']); ?></td>
                    <td><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($row['completed_at']))); ?></td>
                </tr>
                <?php
                $rank++;
            }

            if (!$hasRows) { ?>
                <tr>
                    <td colspan="4">No boss clears recorded yet.</td>
                </tr>
            <?php } ?>
        </table>

        <div class="actions">
            <a href="home.php" class="button">HOME</a>
            <?php if (isset($_SESSION['username'])) { ?>
                <a href="my_score.php" class="button">MY BEST TIME</a>
                <a href="index.php" class="button">PLAY</a>
            <?php } else { ?>
                <a href="login.php" class="button">LOG IN</a>
            <?php } ?>
        </div>
    </main>

        <script>
            let buttons = document.querySelectorAll(".button");
            let sfxHover = document.getElementById("sfx_hover");

            buttons.forEach(b => {
                b.addEventListener("mouseover", function() {
                    b.style.backgroundColor = "rgb(255, 255, 135)";
                    sfxHover.currentTime = 0;
                    sfxHover.play();
                });
                b.addEventListener("mouseout", function() {
                    b.style.backgroundColor = "aquamarine";
                });
            });
        </script>
    </body>
</html>
