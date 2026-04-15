<html>
	<head>
		<title>Home</title>
        <link rel="stylesheet" href="design.css"/>
	</head>

	<body>

        <h1>: P i x e l . P r o g r e s s i o n :</h1>

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