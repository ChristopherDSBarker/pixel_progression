<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pixel Progression</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="game-container">
    <canvas id="gameCanvas" width="480" height="720"></canvas>

    <div id="ui-panel">
        <h2 id="levelStatus" style="display: none;">Level Complete</h2>

        <p id="resourceDisplay">Candies: 0</p>
        <p id="healthDisplay">Health: 240</p>

        <div id="upgrade-buttons" style="display: none;">
            <button id="btnUpgradeHealth" class="ui-button">More Health (10 Candies)</button>
            <button id="btnUpgradeJump" class="ui-button">More Jump Height (10 Candies)</button>
            <button id="btnUpgradeSpeed" class="ui-button">More Speed (10 Candies)</button>
        </div>

        <p id="storyText"></p>

        <!-- optional logout -->
        <br>
        <a href="logout.php">Logout</a>
    </div>
</div>

<script src="game.js"></script>
</body>
</html>