<html>
	<head>
		<title>Login</title>

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

        <?php 
            //DISPLAYS THIS MESSAGE AFTER REGISTERING FOR AN ACCOUNT
            if (isset($_GET['created'])) {
                ?>
                <div class="logodiv" style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 35px">Account created, please log in.</span><br><br>
                    <img src="conceptlogo.png">
                </div>
                <br>
                <?php
            } else {
                ?>
                <div class="logodiv" style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 35px">Log in here.</span><br><br>
                    <img src="conceptlogo.png">
                </div>
                <br>
                <?php
            }
            //REQUEST TO RECEIVE USERNAME AND PASSWORD ENTERED
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
                $login = false;
                //CHECKS IF THERE IS A MATCHING ACCOUNT WITH THE GIVEN USERNAME AND PASSWORD
                while($record = $result->fetchArray()) {
                    $passcheck = $record['L_PASSWORD'];
                    $usercheck = $record['L_USERNAME'];
                    if ($usercheck === $username && $passcheck === $password) {
                        $login = true;
                    }
                }
                //LOGS IN OR FAILS
                if ($login) {
                    session_start();
                    $_SESSION['username'] = $username;
                    header("Location: home.php?log=1");
                    exit();
                } else {
                    ?>
                    <span style="color: red; border-bottom: none">Username or password is incorrect.</span><br><br>
                    <?php
                }
            }
        ?>

            <!-- FORM TO LOG IN -->
            <table>
                <!-- SENDS USERNAME AND PASSWORD TO SERVER -->
                <form method="POST" action="login.php">
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
                            <button type="submit">Log in</button> 
                        </td>
                    </tr>
                </form>
            </table>
            <!-- IF USER DOESN'T HAVE AN ACCOUNT, TAKES THEM TO register.php -->
            <p>Don't have an account? <a href="register.php">Create one here!</a></p>
            <!-- TAKES THE USER TO home.php -->
            <a href="home.php">Home!</a><br><br>

        </div>
	</body>
</html>