
<html>
	<head>
		<title>Register</title>


        <link rel="icon" type="image/png" href="conceptlogo.png"/>
        <link rel="stylesheet" href="style_login.css"/>
	</head>

	<body style="background-color: teal; border: 10px double black; padding: 15px">

        <h1>
            <u style="color: rgb(255, 255, 135); text-shadow: 2px 2px 2px black">
                <span style="color: rgb(255, 255, 135); border-bottom: none; text-shadow: 2px 2px 2px black">P i x e l</span><br>
            </u>
            <u style="color: skyblue; text-shadow: 2px 2px 2px black">
                <span style="color: skyblue; border-bottom: none; text-shadow: 2px 2px 2px black">P r o g r e s s i o n</span>
            </u>
        </h1>

        <!-- BORDER WRAPPED AROUND FORM, LOGO, AND MORE -->
        <div class="content">

            <div class="logodiv" style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 35px">Register for an account!</span><br><br>
                <img src="conceptlogo.png">
            </div>
            <br>

        <?php 
            //RECEIVES SERVER REQUEST FOR USERNAME AND PASSWORD
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $file_exist = file_exists("login.db");

                if (!$file_exist) {
                    include "init_db.php";
                }

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
                    <span style="color: red; border-bottom: none">An account with that username already exists.</span><br><br>
                    <?php
                }
            }
        ?>

            <table>
                <!-- SENDS USERNAME AND PASSWORD TO SERVER -->
                <form method="POST" action="register.php">
                    <tr>
                        <td> 
                            <span style="padding: 0px">Username:</span> 
                            <input style="border: 2px solid black" type="text" name="username" id="username" required><br><br> 
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <span style="padding: 0px">Password:</span> 
                            <input style="border: 2px solid black" type="password" name="password" id="password" required><br><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit">Register</button> 
                        </td>
                    </tr>
                </form>
            </table>
            <!-- IF THE USER HAS AN ACCOUNT, TAKES LINK TO login.php -->
            <p>Have an account? <a href="login.php">Log in here!</a></p>
            <!-- TAKES USER TO home.php -->
            <a href="home.php">Home!</a><br><br>

        </div>
	</body>
</html>