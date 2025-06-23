<?php
session_start();

// Leaderboard-bestand
$leaderboard_file = 'leaderboard.txt';

// Reset elke 10 minuten
$reset_interval = 600;
if (file_exists($leaderboard_file)) {
    if (time() - filemtime($leaderboard_file) > $reset_interval) {
        file_put_contents($leaderboard_file, '');
    }
}

// Verwerk invoer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['naam']) && !empty($_POST['tijd'])) {
    $naam = htmlspecialchars($_POST['naam']);
    $tijd = htmlspecialchars($_POST['tijd']);
    file_put_contents($leaderboard_file, "$naam - $tijd\n", FILE_APPEND);
}

// Lees leaderboard
$leaderboard = file_exists($leaderboard_file) ? file($leaderboard_file, FILE_IGNORE_NEW_LINES) : [];

// DATABASE-opslag
if (isset($_SESSION['starttijd'])) {
    $eindtijd = time();
    $speeltijd = $eindtijd - $_SESSION['starttijd'];

    // Vul je gegevens correct in
    $conn = new mysqli("localhost", "root", "", "escaperoom2");
    if ($conn->connect_error) {
        die("Connectie mislukt: " . $conn->connect_error);
    }

    $naam = isset($_POST['naam']) ? $_POST['naam'] : 'Gast';
    if ($stmt = $conn->prepare("INSERT INTO leaderboard (naam, tijd) VALUES (?, ?)")) {
        $stmt->bind_param("si", $naam, $speeltijd);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
   // unset($_SESSION['starttijd']);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Je hebt gewonnen!</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="screen active">
    <h1>🎉 Je bent ontsnapt!</h1>
    <p>Je hebt het raadsel opgelost en vrijheid herwonnen!</p>
    <form id="leaderboardForm" method="post">
      <input type="text" name="naam" placeholder="Jouw naam" required>
      <input type="hidden" name="tijd" id="tijdInput">
      <button type="submit">Voeg toe aan leaderboard</button>
    </form>
    <h2>Leaderboard (reset elke 10 minuten)</h2>
    <ol>
      <?php foreach ($leaderboard as $entry): ?>
        <li><?php echo htmlspecialchars($entry); ?></li>
      <?php endforeach; ?>
    </ol>
    <button onclick="goHome()">Terug naar Start</button>
  </div>

  <script>
    function goHome() {
      window.location.href = "index.php";
    }

    document.addEventListener('DOMContentLoaded', function() {
      const starttijd = localStorage.getItem('starttijd');
      if (starttijd) {
        const eindtijd = Date.now();
        const seconden = Math.floor((eindtijd - starttijd) / 1000);
        const minuten = Math.floor(seconden / 60);
        const restSeconden = seconden % 60;
        document.getElementById('tijdInput').value = `${minuten}m ${restSeconden}s`;
      }
    });
  </script>
</body>
</html>
