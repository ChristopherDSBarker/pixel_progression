<html>
	<head>
		<title>Home</title>

        <link rel="stylesheet" href="design.css"/>
        <link rel="icon" type="image/png" href="conceptlogo.png"/>
	</head>

	<body>

        <h1>
            <u style="color: rgb(255, 255, 135); text-shadow: 2px 2px 2px black">
                <span style="color: rgb(255, 255, 135); border-bottom: none; text-shadow: 2px 2px 2px black">P i x e l</span><br>
            </u>
            <u style="color: skyblue; text-shadow: 2px 2px 2px black">
                <span style="color: skyblue; border-bottom: none; text-shadow: 2px 2px 2px black">P r o g r e s s i o n</span>
            </u>
        </h1>

        <?php
            //DISPLAYS THIS MESSAGE AFTER LOGGING IN
            if (isset($_GET['log'])) {
                session_start();
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Home page . . .</span>
                    <span id="welcome">Welcome, <?php echo $_SESSION['username'] ?></span>
                </div>
                <br>
                <div style="text-align: right;"> <a id="signout" href="home.php">Log out!</a> </div>
                <?php
            } else {
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Home page . . .</span><br><br>
                    <div style="text-align: right;"> <a id="signout" href="login.php">Log in here!</a> </div>
                </div>
                <?php
            }
        ?>
	</body>
</html>