<?php session_start() ?>

<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" href="style_home.css?v=1">
        <link rel="icon" type="image/png" href="conceptlogo.png">
	</head>

	<body style="background-color: teal">

        <audio id="sfx_hover" src="assets/sfx_btn_hover.mp3" preload="auto"></audio>

        <div style="background-color: aquamarine; border: 5px black double; padding: 5px">
            <img src="conceptlogo.png"></img>
            
            <h1 id="title1">P i x e l</h1>
            <h1 id="title2"> P r o g r e s s i o n </h1>
        </div>

        <br><br>

        <?php
        //DISPLAYS THIS MESSAGE AFTER LOGGING IN>
        if (isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center;">
                <span style="font-size: 15px; color: white; font-family: monospace; display: inline-block">
                    Welcome, <span style="color: aquamarine"><?php echo $_SESSION['username'] ?></span>
                !</span>
            </div>
        <?php } ?>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <?php 
                if (isset($_SESSION['username'])) { ?>
                <a href="index.php" class="button">START GAME</a> 
            <?php } ?>
        </div>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="htp.html" class="button">HOW TO PLAY</a>
        </div>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="leaderboard.php" class="button">LEADERBOARD</a>
        </div>

        <br>

        <?php if (isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center">
                <a href="my_score.php" class="button">MY BEST TIME</a>
            </div>

            <br>
        <?php } ?>

        <div style="display: flex; justify-content: center; align-items: center">
            <?php
            if (isset($_SESSION['username'])) { ?>
                <a href="logout.php" id="signout" class="button">LOG OUT</a>
            <?php } else { ?>
                <a href="login.php" id="signout" class="button">LOG IN</a>
            <?php } ?>
        </div>

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
