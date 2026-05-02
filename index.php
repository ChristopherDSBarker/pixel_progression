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
<body id="indexBody">

<div id="game-container">
    <canvas id="gameCanvas" width="480" height="500"></canvas>

    <div id="ui-panel">
        <h2 id="levelStatus" style="display: none;">Level Complete!</h2>

        <p id="resourceDisplay">Candies: 0</p>
        <p id="healthDisplay">Health: 100</p>

        <div id="next-level-button" style="display: none;">
            <button id="btnNextLevel" class="ui-button">Keep going!</button>
        </div>

        <br>
        
        <div id="upgrade-buttons" style="display: none;">
            <button id="btnUpgradeHealth" class="ui-button">Increase Max Health (15 Candies)</button>
            <button id="btnUpgradeJump" class="ui-button">Increase Jump Height (20 Candies)</button>
            <button id="btnUpgradeSpeed" class="ui-button">Increase Speed (10 Candies)</button>
        </div>

        <p id="storyText"></p>

        <h1 id="overText" 
            style="
            text-align: center; 
            font-size: 50px; 
            display: none
            ">
            GAME <span style="color: red">OVER</span></h1>

        <br>
        
        <div id="retry" style="display: none; justify-content: center; align-items: center">
            <a href="index.php" class="menu-button">RETRY?</a> 
        </div>

        <br>

        <div id="home" style="display: none; justify-content: center; align-items: center">
            <a href="home.php" class="menu-button">RETURN HOME</a> 
        </div>

        <!-- optional logout -->
        <br>
        <a id="logout" href="logout.php">Logout</a>
    </div>
</div>

<img id="e_knight_r" src="assets/enem_knight_r.png" style="display: none">
<img id="e_knight_l" src="assets/enem_knight_l.png" style="display: none">
<img id="e_ninja_r" src="assets/enem_ninja_r.png" style="display: none">
<img id="e_ninja_l" src="assets/enem_ninja_l.png" style="display: none">
<img id="e_wizard_r" src="assets/enem_wizard_r.png" style="display: none">
<img id="e_wizard_l" src="assets/enem_wizard_l.png" style="display: none">
<img id="e_marksman_r" src="assets/enem_marksman_r.png" style="display: none">
<img id="e_marksman_l" src="assets/enem_marksman_l.png" style="display: none">

<img id="e_fireball" src="assets/enem_fireball.png" style="display: none">
<img id="e_bullet_r" src="assets/enem_bullet_r.png" style="display: none">
<img id="e_bullet_l" src="assets/enem_bullet_l.png" style="display: none">

<img id="p_heart" src="assets/powerup_heart.png" style="display: none">
<img id="p_shield" src="assets/powerup_shield.png" style="display: none">

<audio id="mus_game" src="assets/mus_game.ogg" preload="auto"></audio>
<audio id="mus_gameover" src="assets/mus_gameover.ogg" preload="auto"></audio>

<audio id="sfx_jump" src="assets/sfx_player_jump.mp3" preload="auto"></audio>
<audio id="sfx_hurt" src="assets/sfx_player_hurt.mp3" preload="auto"></audio>
<audio id="sfx_fireball" src="assets/sfx_wizard_projectile.mp3" preload="auto"></audio>
<audio id="sfx_bullet" src="assets/sfx_marksman_projectile.mp3" preload="auto"></audio>

<audio id="sfx_win" src="assets/sfx_levelcomplete.mp3" preload="auto"></audio>
<audio id="sfx_over" src="assets/sfx_gameover.mp3" preload="auto"></audio>
<audio id="sfx_hover" src="assets/sfx_btn_hover.mp3" preload="auto"></audio>

<audio id="sfx_heart" src="assets/sfx_powerup_health.mp3" preload="auto"></audio>
<audio id="sfx_heartf" src="assets/sfx_powerup_healthfull.mp3" preload="auto"></audio>
<audio id="sfx_shield" src="assets/sfx_powerup_shield.mp3" preload="auto"></audio>

<audio id="sfx_hey" src="assets/sfx_merchant.mp3" preload="auto"></audio>
<audio id="sfx_upgrade" src="assets/sfx_upgrade.mp3" preload="auto"></audio>
<audio id="sfx_comeagain" src="assets/sfx_merchant2.mp3" preload="auto"></audio>
<audio id="sfx_broke" src="assets/sfx_btn_fail.mp3" preload="auto"></audio>

<script src="game.js"></script>
</body>
</html>