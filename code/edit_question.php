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

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vraag = $_POST['question'];
    $antwoord = $_POST['answer'];
    $hint = $_POST['hint'];
    $room = $_POST['roomId'];

    $stmt = $conn->prepare("UPDATE questions SET question=?, answer=?, hint=?, roomId=? WHERE id=?");
    $stmt->bind_param("sssii", $vraag, $antwoord, $hint, $room, $id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}


$result = $conn->query("SELECT * FROM questions WHERE id = $id");
$question = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head><title>Bewerk Vraag</title></head>
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
<body>
<h2>Vraag Bewerken (ID <?= $id ?>)</h2>
<form method="post">
    Vraag:<br><textarea name="question" rows="3" cols="50"><?= htmlspecialchars($question['question']) ?></textarea><br>
    Antwoord:<br><input type="text" name="answer" value="<?= htmlspecialchars($question['answer']) ?>"><br>
    Hint:<br><textarea name="hint" rows="2" cols="50"><?= htmlspecialchars($question['hint']) ?></textarea><br>
    Room ID:<br><input type="number" name="roomId" value="<?= $question['roomId'] ?>"><br><br>
    <button type="submit">Opslaan</button>
</form>
<a href="admin_dashboard.php">Terug</a>
</body>
</html>
