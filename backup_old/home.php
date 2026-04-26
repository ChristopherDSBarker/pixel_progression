<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

<h1>Pixel Progression</h1>

<?php if (isset($_SESSION['user'])): ?>
    <p>Welcome, <?php echo $_SESSION['user']; ?></p>
    <a href="index.php">Play Game</a><br><br>
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.html">Login / Register</a>
<?php endif; ?>

</body>
</html>