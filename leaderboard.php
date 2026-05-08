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

<html>
	<head>
		<title>My Best Time</title>
        <link rel="icon" type="image/png" href="conceptlogo.png">
        <link rel="stylesheet" href="style_home.css">
        <style>
        body {
            background-color: teal;
            font-family: monospace;
        }

        .board {
            width: min(760px, 90vw);
            margin: 30px auto;
            background-color: aquamarine;
            border: 5px ridge black;
            box-shadow: 3px 3px 3px black;
            padding: 10px;
        }

        table {
            border: 2px solid black;
            padding: 5px;
            background-color: darkcyan;
        }

        th,
        td {
            border: 2px solid black;
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
        h2 {
            position: relative;
            text-align: center;

            font-family: monospace;
            font-size: 35;

            color: rgb(255, 255, 135);
            text-shadow: 2px 2px 2px black;
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

        <main class="board">
            <h2>
                <u style="color: rgb(255, 255, 135); text-shadow: 2px 2px 2px black">
                    Top 5 Best Times  
                </u>
            </h2>

            <table>
                <tr style="background-color: darkturquoise">
                    <th><u>Rank</u></th>
                    <th><u>User</u></th>
                    <th><u>Best Time</u></th>
                    <th><u>Completed</u></th>
                </tr>

                <?php
                $rank = 1;
                $hasRows = false;

                while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                    $hasRows = true;
                    ?>
                    <tr style="background-color: darkslategray">
                        <?php
                            if ($rank === 1) {
                                ?>
                                <td style="color: gold"><?php echo $rank; ?></td>
                                <?php
                            } else if ($rank === 2) {
                                ?>
                                <td style="color: silver"><?php echo $rank; ?></td>
                                <?php
                            } else if ($rank === 3) {
                                ?>
                                <td style="color: rgb(205, 127, 50)"><?php echo $rank; ?></td>
                                <?php
                            } else {
                                ?>
                                <td><?php echo $rank; ?></td>
                                <?php
                            }
                        ?>
                        <td style="color: rgb(255, 255, 135)"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td style="color: skyblue"><?php echo htmlspecialchars($row['time_formatted']); ?></td>
                        <td><u style="color: mediumseagreen"><?php echo htmlspecialchars(date('M j, Y g:i A', strtotime($row['completed_at']))); ?></u></td>
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

        </main>

        <div class="actions">
            <a href="home.php" class="button" style="border: 3px solid black">HOME</a>
            <?php if (isset($_SESSION['username'])) { ?>
                <a href="my_score.php" class="button" style="border: 3px solid black">MY BEST TIME</a>
            <?php } else { ?>
                <a href="login.php" class="button" style="border: 3px solid black">LOG IN</a>
            <?php } ?>
        </div>

        <script>
            let homeButton = document.getElementsByClassName("button")[0];
            let secondButton = document.getElementsByClassName("button")[1];

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

            secondButton.addEventListener("mouseover", function() {
                secondButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            secondButton.addEventListener("mouseout", function() {
                secondButton.style.backgroundColor = "aquamarine";
            });
        </script>
	</body>
</html>