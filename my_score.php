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

<html>
	<head>
		<title>My Best Time</title>
        <link rel="icon" type="image/png" href="conceptlogo.png">
        <link rel="stylesheet" href="style_home.css">
        <style>
            body {
                background-color: teal;
                font-family: monospace;
                border: 10px double black;
            }

            .score-card {
                width: min(640px, 90vw);
                margin: 30px auto;
                background-color: aquamarine;
                border: 5px ridge black;
                box-shadow: 3px 3px 3px black;
                padding: 10px;
                text-align: center;
            }

            .time {
                display: block;
                font-size: 40px;
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

            h2 {
                position: relative;
                text-align: center;

                font-family: monospace;
                font-size: 35;

                color: rgb(255, 255, 135);
                text-shadow: 2px 2px 2px black;
            }
            p {
                font-family: monospace;
                font-size: 25;
            }
        </style>
	</head>

	<body style="background-color: teal; border: 10px double black; padding: 15px">

        <audio id="sfx_hover" src="assets/sfx_btn_hover.mp3" preload="auto"></audio>

        <h1>
            <u style="color: rgb(255, 255, 135); text-shadow: 2px 2px 2px black">
                <span style="color: rgb(255, 255, 135); border-bottom: none; text-shadow: 2px 2px 2px black">P i x e l</span><br>
            </u>
            <u style="color: skyblue; text-shadow: 2px 2px 2px black">
                <span style="color: skyblue; border-bottom: none; text-shadow: 2px 2px 2px black">P r o g r e s s i o n</span>
            </u>
        </h1>

        <main class="score-card">
            <h2>
                <u style="color: rgb(255, 255, 135); text-shadow: 2px 2px 2px black">
                    My Best Time    
                </u>
            </h2>

            <?php if (!$username) { ?>
                <p>Log in to save and view your best boss clear time.</p>
            <?php } else if ($best) { ?>
                <p><?php echo htmlspecialchars($username); ?></p>
                <span style="color: skyblue;" class="time"><?php echo htmlspecialchars($best['time_formatted']); ?></span>
                <p>Rank: <?php echo htmlspecialchars($rank); ?></p>
                <p><u style="color: mediumseagreen;">Completed: <?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($best['completed_at']))); ?></u></p>
            <?php } else { ?>
                <p>No boss clear saved yet.</p>
            <?php } ?>

        </main>

        <div class="actions">
            <a href="home.php" class="button" style="border: 3px solid black">HOME</a>
            <a href="leaderboard.php" class="button" style="border: 3px solid black">LEADERBOARD</a>
        </div>

        <script>
            let homeButton = document.getElementsByClassName("button")[0];
            let leaderButton = document.getElementsByClassName("button")[1];

            let sfxHover = document.getElementById("sfx_hover");
            let sfxClick = document.getElementById("sfx_click");
            
            homeButton.addEventListener("mouseover", function() {
                homeButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            homeButton.addEventListener("mouseout", function() {
                homeButton.style.backgroundColor = "aquamarine";
            });

            leaderButton.addEventListener("mouseover", function() {
                leaderButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            leaderButton.addEventListener("mouseout", function() {
                leaderButton.style.backgroundColor = "aquamarine";
            });
        </script>
	</body>
</html>