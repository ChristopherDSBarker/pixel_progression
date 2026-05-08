<?php session_start() ?>

<html>
	<head>
		<title>Home</title>
        <link rel="icon" type="image/png" href="conceptlogo.png">
        <link rel="stylesheet" href="style_home.css">
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

        <?php
        //DISPLAYS THIS MESSAGE AFTER LOGGING IN>
        if (isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center;">
                <span style="font-size: 15px; color: white; font-family: monospace; display: inline-block; border-bottom: none">
                    Welcome, <span style="color: aquamarine; border-bottom: none"><?php echo $_SESSION['username'] ?></span>
                !</span>
            </div>
            <br>
        <?php } ?>

        <?php if (isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center">
                    <a href="index.php" class="button" style="border: 3px solid black">START GAME</a> 
            </div>
            <br>
        <?php } ?>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="htp.html" class="button" style="border: 3px solid black">HOW TO PLAY</a>
        </div>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="leaderboard.php" class="button" style="border: 3px solid black">LEADERBOARD</a>
        </div>

        <br>

        <?php if (isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center">
                <a href="my_score.php" class="button" style="border: 3px solid black">MY BEST TIME</a>
            </div>
            <br>
        <?php } ?>

        <div style="display: flex; justify-content: center; align-items: center">
            <?php
            if (isset($_SESSION['username'])) { ?>
                <a href="logout.php" id="signout" class="button" style="border: 3px solid black">LOG OUT</a>
            <?php } else { ?>
                <a href="login.php" id="signout" class="button" style="border: 3px solid black">LOG IN</a>
            <?php } ?>
        </div>

        <br>


        <?php
        //DISPLAYS THIS MESSAGE AFTER LOGGING IN>
        if (!isset($_SESSION['username'])) { ?>
            <div style="display: flex; justify-content: center; align-items: center">
                <img src="conceptlogo.png"/>
            </div>
        <?php } ?>

        <script>

            let playButton = document.getElementsByClassName("button")[0];
            let logButton = document.getElementsByClassName("button")[1];
            let htpButton = document.getElementsByClassName("button")[2];
            let leaderButton = document.getElementsByClassName("button")[3];
            let bestButton = document.getElementsByClassName("button")[4];

            let sfxHover = document.getElementById("sfx_hover");
            let sfxClick = document.getElementById("sfx_click");
            
            playButton.addEventListener("mouseover", function() {
                playButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            playButton.addEventListener("mouseout", function() {
                playButton.style.backgroundColor = "aquamarine";
            });

            logButton.addEventListener("mouseover", function() {
                logButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            logButton.addEventListener("mouseout", function() {
                logButton.style.backgroundColor = "aquamarine";
            });

            htpButton.addEventListener("mouseover", function() {
                htpButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            htpButton.addEventListener("mouseout", function() {
                htpButton.style.backgroundColor = "aquamarine";
            });

            leaderButton.addEventListener("mouseover", function() {
                leaderButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            leaderButton.addEventListener("mouseout", function() {
                leaderButton.style.backgroundColor = "aquamarine";
            });

            bestButton.addEventListener("mouseover", function() {
                bestButton.style.backgroundColor = "rgb(255, 255, 135)";
                sfxHover.currentTime = 0;
                sfxHover.play();
            });

            bestButton.addEventListener("mouseout", function() {
                bestButton.style.backgroundColor = "aquamarine";
            });
            

        </script>
	</body>
</html>