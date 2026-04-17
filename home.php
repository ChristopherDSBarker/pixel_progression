<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" href="design_home.css"/>
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
                <span id="welcome">Welcome, <?php echo $_SESSION['username'] ?></span>
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
            let button = document.getElementByClassName("button");
            
            button.addEventListener("mouseover", () => {
                button.style.backgroundColor = "rgb(255, 255, 135)";
            })

            button.addEventListener("mouseout", () => {
                button.style.backgroundColor = "aquamarine";
            })
            

        </script>


 
	</body>
</html>