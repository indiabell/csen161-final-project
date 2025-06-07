<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fido Finder - Home</title>
</head>
<body>
    <h1> Fido Finder</h1>
    <p>Fetch the Dog that Matches You!</p>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>

    <nav>
        <a href="home.php">Home</a>
        <a href="quiz.php">Take the Personality Quiz</a>
        <a href="logout.php">Find a Furry Friend Fast!</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div style="height: 150px; background-color: #ddd; margin-top: 20px;">
    </div>

    <div style="margin-top: 20px;">
        <form action="result.php" method="get">
            <button type="submit">Find a Furry Friend</button>
        </form>
        <form action="quiz.php" method="get">
            <button type="submit">Personality Quiz</button>
        </form>
    </div>
</body>
</html>