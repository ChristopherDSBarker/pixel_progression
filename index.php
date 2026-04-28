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
    <canvas id="gameCanvas" width="480" height="500"></canvas>

    <div id="ui-panel">
        <h2 id="levelStatus" style="display: none;">Level Complete</h2>

        <p id="resourceDisplay">Candies: 0</p>
        <p id="healthDisplay">Health: 240</p>

        <div id="next-level-button" style="display: none;">
            <button id="btnNextLevel" class="ui-button">Keep going!</button>
        </div>

        <br>
        
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

<img id="e_knight_r" src="assets/enem_knight_r.png" style="display: none">
<img id="e_knight_l" src="assets/enem_knight_l.png" style="display: none">

<audio autoplay loop>
    <source src="assets/mus_maingame.ogg" type="audio/ogg">
</audio>

<audio id="sfx_jump" src="assets/sfx_player_jump.mp3" preload="auto"></audio>
<audio id="sfx_hurt" src="assets/sfx_player_hurt.mp3" preload="auto"></audio>

<audio id="sfx_win" src="assets/sfx_levelcomplete.mp3" preload="auto"></audio>

<audio id="sfx_hey" src="assets/sfx_merchant.mp3" preload="auto"></audio>
<audio id="sfx_upgrade" src="assets/sfx_upgrade.mp3" preload="auto"></audio>
<audio id="sfx_comeagain" src="assets/sfx_merchant2.mp3" preload="auto"></audio>

<script src="game.js"></script>
</body>
</html>