<?php
session_start();

// Verbinding met de database
$host = 'localhost';
$db   = 'escaperoom2';
$user = 'root';
$pass = ''; // Pas aan indien nodig
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

// Inloggen verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login gelukt, sessie aanmaken
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: room_1.php"); // Verander dit naar je gewenste pagina
        exit;
    } else {
        $error = "Ongeldige inloggegevens";
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
    <title>Inloggen</title>
</head>
<body>
    <div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Wachtwoord:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Inloggen</button>
        <p>Geen account? <a href="registreer.php">Registreer hier</a></p>
    </form>
</div>
</body>
</html>
