<?php
// Verbinding maken met de database
$host = 'localhost';
$db   = 'escaperoom2';
$user = 'root';
$pass = ''; // pas dit aan naar je eigen wachtwoord
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Databaseverbinding mislukt: " . $e->getMessage();
    exit;
}

// Registratie verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpele validatie
    if (!empty($username) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            echo "Registratie geslaagd!";
        } else {
            echo "Er is iets misgegaan.";
        }
    } else {
        echo "Vul geldige gegevens in.";
    }
}
?>
 <style>
    body, html {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden; /* voorkomt scrollen */
    }

    body {
      background-image: url('loginfoto.jpg');
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
      color: black;
    }

    .login-container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        margin: auto;
        margin-top: 100px;
    }
  </style>

<!DOCTYPE html>
<html>
<head>
    <title>Registreren</title>
</head>
<body>
    <div class="login-container">
    <h2>Registratieformulier</h2>
    <form method="post" action="">
        <label>Gebruikersnaam:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Wachtwoord:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Registreren</button>
    </form>
    <a href="index.php">Terug naar homepage</a>
</body>
</html>
