<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pixel Progression</title>
    <link rel="icon" type="image/png" href="conceptlogo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="game-container">
    <h2 id="levelStatus" style="display: none; align-items: center; color: green">Level Complete (see below)</h2>
    <canvas id="gameCanvas" width="480" height="720"></canvas>

    <div id="ui-panel">

        <p id="resourceDisplay">Candies: </p>
        <p id="healthDisplay">Health: </p>

        <p id="storyText"></p>
        <div id="upgrade-buttons" style="display: none;">
            <button id="btnUpgradeHealth" class="ui-button">More Health (10 Candies)</button>
            <button id="btnUpgradeJump" class="ui-button">More Jump Height (10 Candies)</button>
            <button id="btnUpgradeSpeed" class="ui-button">More Speed (10 Candies)</button>
            <button id="btnNextLevel" class="ui-button">Next Level (0 Candies)</button>
        </div>

        <p id="poor" style="display: none">You do not have enough candies to get that ability!</p>

        <?php
            session_start();

            if (!isset($_SESSION['username'])) {
                header("Location: home.php");
                exit();
            }

            $username = $_SESSION['username'];
            $statsdb = new SQLite3("login.db");
            $query = "SELECT * FROM STATS WHERE L_USERNAME = '$username'";
            $success = $statsdb->query($query);

            $row = $success->fetchArray(SQLITE3_ASSOC);
            if ($row) {
                $level = $row['S_LEVEL'];
                $jumpheight = $row['S_JUMPHEIGHT'];
                $speed = $row['S_SPEED'];
                $health = $row['S_HEALTH'];
                $candies = $row['S_CANDIES'];
                ?>
                <script src="game.js">
                </script>
                <script>
                    player.level = <?php echo $level; ?>;
                    player.jumpHeight = <?php echo $jumpheight; ?>;
                    player.speed = <?php echo $speed; ?>;
                    player.health = <?php echo $health; ?>;
                    candies = <?php echo $candies; ?>;
                    loadLevel(player.level);
                    startGame();
                </script>
                <?php
            } else {
                ?>
                <script src="game.js">
                </script>
                <script>
                    loadLevel(1);
                    startGame();
                </script>
                <?php
            }
            
        ?>

        <!-- optional logout -->
        <br>
        <button style="background-color: aquamarine; border: 2px solid black; font-family: monospace" onclick="logoutAndSave()">Log out</button>
    </div>
</div>
</body>
</html>