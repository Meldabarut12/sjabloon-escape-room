<?php
require_once('./dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM question WHERE roomId = 2");
  $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room 2</title>
  <link rel="stylesheet" href="style.css">
</head>

<h1>Tijd over: <span id="timer">15:00</span></h1>
  <button id="startBtn">Begin het spel</button>
  <script src="timer.js"></script>

<body>

  <div class="container">
    <?php foreach ($questions as $index => $question) : ?>
    <div class="box box<?php echo $index + 1; ?>" onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>" data-question="<?php echo htmlspecialchars($question['question']); ?>"
      data-answer="<?php echo htmlspecialchars($question['answer']); ?>">
      <?php echo htmlspecialchars($question['question']); ?>
    </div>
    <br>
    <?php endforeach; ?>
  </div>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="question"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <p id="feedback"></p>
  </section>

  <script src="script.js"></script>

</body>

</html>