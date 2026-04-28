
<html>
	<head>
		<title>Register</title>


        <link rel="icon" type="image/png" href="conceptlogo.png"/>
        <link rel="stylesheet" href="style_login.css"/>
	</head>

	<body style="background-color: teal">

        <div style="background-color: aquamarine; border: 5px black double; padding: 5px">
            <h1 id="title1">P i x e l</h1>
            <h1 id="title2"> P r o g r e s s i o n </h1>
        </div>

        <br><br>

        <!-- BORDER WRAPPED AROUND FORM, LOGO, AND MORE -->
        <div class="content">

            <div class="logodiv" style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 35px; font-family: monospace">Register for an account!</span><br><br>
                <img src="conceptlogo.png">
            </div>
            <br>

        <?php 
            //RECEIVES SERVER REQUEST FOR USERNAME AND PASSWORD
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $logindb = new SQLite3("login.db");

                $query2 = "SELECT * FROM LOGININFO";
                $result = $logindb->query($query2);
                $accexists = false;
                //CHECKS IF AN ACCOUNT EXISTS WITH THE GIVEN USERNAME
                while($record = $result->fetchArray()) {
                    $passcheck = $record['L_PASSWORD'];
                    $usercheck = $record['L_USERNAME'];
                    if ($usercheck === $username) {
                        $accexists = true;
                    }
                }
                //CREATES OR FAILS TO CREATE ACCOUNT
                if ($accexists === false) {
                    $query = "INSERT INTO LOGININFO (L_PASSWORD, L_USERNAME) VALUES ('$password', '$username')";
                    $logindb->exec($query);
                    header("Location: login.php?created=1");
                    exit();
                } else {
                    ?>
                    <span style="color: red; border-bottom: none; font-family: monospace">An account with that username already exists.</span><br><br>
                    <?php
                }
            }
        ?>

            <table>
                <!-- SENDS USERNAME AND PASSWORD TO SERVER -->
                <form method="POST" action="register.php">
                    <tr>
                        <td> 
                            <span style="padding: 0px; font-family: monospace">Username:</span> 
                            <input style="border: 2px solid black" type="text" name="username" id="username" required><br><br> 
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <span style="padding: 0px; font-family: monospace">Password:</span> 
                            <input style="border: 2px solid black" type="password" name="password" id="password" required><br><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" style="font-family: monospace">Register</button> 
                        </td>
                    </tr>
                </form>
            </table>
            <!-- IF THE USER HAS AN ACCOUNT, TAKES LINK TO login.php -->
            <p style="font-family: monospace">Have an account? <a href="login.php" style="font-family: monospace">Log in here!</a></p>
            <!-- TAKES USER TO home.php -->
            <a href="home.php" style="font-family: monospace">Return Home!</a><br><br>

        </div>
	</body>
</html>