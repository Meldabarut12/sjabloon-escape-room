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

  <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden; /* voorkomt scrollen */
    }

    body {
      background-image: url('download111.jpg');
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
</head>


<body>
  <div class="screen active">
    <h1>🏰 Middeleeuwse Escape Room</h1>
    <p>Ben jij slim genoeg om te ontsnappen uit het kasteel?</p>
    
    <p>Om te beginnen, registreer je of log in als je al een account hebt.</p>
    <form action="registreer.php" method="get">
      <button type="submit">Registreren</button>
    </form>
    <form action="login.php" method="get">
      <button type="submit">Inloggen</button>
    </form>
    <form action="admin_login.php" method="get">
      <button type="submit">admin login </button>
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



