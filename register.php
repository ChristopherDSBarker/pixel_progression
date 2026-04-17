<!-- TEST -->
<html>
	<head>
		<title>Register</title>

        <link rel="stylesheet" href="design.css"/>
	</head>

	<body style="background-color: teal">

        <h1>: P i x e l . P r o g r e s s i o n :</h1>

        <!-- BORDER WRAPPED AROUND FORM, LOGO, AND MORE -->
        <div style="background-color: aquamarine; border: 5px black double; padding: 5px">

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

                    <?php
                }
            }
        ?>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Register for an account!</span><br><br>
                <img src="conceptlogo.png">
            </div>
            <br>

            <table>
                <!-- SENDS USERNAME AND PASSWORD TO SERVER -->
                <form method="POST" action="register.php">
                    <tr>
                        <td> Username: <input type="text" name="username" id="username" required><br><br> </td>
                    </tr>
                    <tr>
                        <td> Password: <input type="password" name="password" id="password" required><br><br> </td>
                    </tr>
                    <tr>
                        <td> <button type="submit">Register</button> </td>
                    </tr>
                </form>
            </table>
            <!-- IF THE USER HAS AN ACCOUNT, TAKES LINK TO login.php -->
            <p>Have an account? <a href="login.php">Log in here!</a></p>
            <!-- TAKES USER TO home.php -->
            <a href="home.php">Home!</a>

        </div>
	</body>
</html>
