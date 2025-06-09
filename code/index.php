<?php
$leaderboard_file = 'leaderboard.txt';

// Lees leaderboard
$leaderboard = file_exists($leaderboard_file) ? file($leaderboard_file, FILE_IGNORE_NEW_LINES) : [];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Middeleeuwse Escape Room</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="screen active">
    <h1>🏰 Middeleeuwse Escape Room</h1>
    <p>Ben jij slim genoeg om te ontsnappen uit het kasteel?</p>
    
    <form id="naamForm" action="room_1.php" method="get">
      <input type="text" name="username" id="username" placeholder="Voer je gebruikersnaam in" required>
      <button type="submit">Start het avontuur!</button>
    </form>

    <h2>Leaderboard</h2>
    <ol>
      <?php foreach ($leaderboard as $entry): ?>
        <li><?php echo htmlspecialchars($entry); ?></li>
      <?php endforeach; ?>
    </ol>
  </div>

  <script src="script.js"></script>
</body>
</html>