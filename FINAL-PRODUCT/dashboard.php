<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include 'templates/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fido Finder - Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <h1> Fido Finder</h1>
    <p><em>Fetch the Dog that Matches You!</em></p>

    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>

    <div>
        <form action="results.php" method="get">
            <button type="submit">Find a Furry Friend</button>
        </form>
        <form action="quiz.php" method="get">
            <button type="submit">Personality Quiz</button>
        </form>
    </div>
</body>
</html>