<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    $username = $_SESSION['username'];
    $statsdb = new SQLite3("login.db");
    $query = "SELECT * FROM STATS WHERE L_USERNAME = '$username'";
    $success = $statsdb->query($query);

    $row = $success->fetchArray(SQLITE3_ASSOC);
    if ($row) { //resets seconds to 0 and other stats to default values if user exists in stats table
        $update = "UPDATE STATS SET S_LEVEL = '1', S_JUMPHEIGHT = '300', S_SPEED = '150',
            S_HEALTH = '100', S_CANDIES = '0', S_SECONDS = '0' WHERE L_USERNAME = '$username'";
        $statsdb->exec($update);
        header("Location: index.php");
        exit();
    } else { //add new entry if user does not exist in stats table
        header("Location: index.php");
        exit();
    }
?>