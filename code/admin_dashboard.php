<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "escaperoom2");
if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}

// Haal leaderboard op
$players = $conn->query("SELECT * FROM leaderboard ORDER BY tijd ASC");

// Haal vragen op
$questions = $conn->query("SELECT * FROM questions ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>

  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden; /* voorkomt scrollen */
    }

    body {
      background-image: url('fotoleader.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
      width: 100vw;
      height: 100vh;
    }

    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin: 30px;
      color: white;
    }
  </style>
<body>
<h1>👨‍💼 Admin Dashboard</h1>

<h2>Leaderboard</h2>
<table border="1">
    <tr><th>ID</th><th>Naam</th><th>Tijd (s)</th></tr>
    <?php while ($row = $players->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['naam']) ?></td>
            <td><?= $row['tijd'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>Vragen beheren</h2>
<table border="1">
    <tr><th>ID</th><th>Vraag</th><th>Antwoord</th><th>Hint</th><th>Room ID</th><th>Actie</th></tr>
    <?php while ($q = $questions->fetch_assoc()): ?>
        <tr>
            <td><?= $q['id'] ?></td>
            <td><?= htmlspecialchars($q['question']) ?></td>
            <td><?= htmlspecialchars($q['answer']) ?></td>
            <td><?= htmlspecialchars($q['hint']) ?></td>
            <td><?= $q['roomId'] ?></td>
            <td><a href="edit_question.php?id=<?= $q['id'] ?>">Bewerk</a></td>
        </tr>
    <?php endwhile; ?>
</table>


<a href="index.php">Uitloggen</a>
</body>
</html>
