<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" href="style_home.css">
        <link rel="icon" type="image/png" href="conceptlogo.png">
	</head>

	<body style="background-color: teal">

        <div style="background-color: aquamarine; border: 5px black double; padding: 5px">
            <img src="conceptlogo.png"></img>
            
            <h1 id="title1">P i x e l</h1>
            <h1 id="title2"> P r o g r e s s i o n </h1>
        </div>

        <br><br>

        <?php
        //DISPLAYS THIS MESSAGE AFTER LOGGING IN>
        if (isset($_GET['log'])) {
            session_start();
               ?>
            <div style="display: flex; justify-content: center; align-items: center;">
                <span style="font-size: 15px; color: white; font-family: monospace; display: inline-block">
                    Welcome,  <span style="color: aquamarine"><?php echo $_SESSION['username'] ?></span>
                </span>
            </div>
        <?php } ?>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <a href="index.html" class="button">START GAME</a>
        </div>

        <br>

        <div style="display: flex; justify-content: center; align-items: center">
            <?php
            if (isset($_GET['log'])) {
                session_start();
                ?>
                <a href="home.php" id="signout" class="button">LOG OUT</a>
            <?php } else { ?>
                <a href="login.php" id="signout" class="button">LOG IN</a>
            <?php } ?>
        </div>

        <script>
            let playButton = document.getElementsByClassName("button")[0];
            let logButton = document.getElementsByClassName("button")[1];
            
            playButton.addEventListener("mouseover", function() {
                playButton.style.backgroundColor = "rgb(255, 255, 135)";
            });

            playButton.addEventListener("mouseout", function() {
                playButton.style.backgroundColor = "aquamarine";
            });

            logButton.addEventListener("mouseover", function() {
                logButton.style.backgroundColor = "rgb(255, 255, 135)";
            });

            logButton.addEventListener("mouseout", function() {
                logButton.style.backgroundColor = "aquamarine";
            });
            

        </script>


 
	</body>
</html>