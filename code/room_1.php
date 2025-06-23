<?php
require_once('dbcon.php');
session_start();
ob_start();

// Zet hier de juiste antwoorden op volgorde
$juisteAntwoorden = ['antwoord1', 'antwoord2', 'antwoord3'];

// Zet de score op 0 als deze nog niet bestaat
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

// Controleer of alle antwoorden zijn gegeven
if (isset($_SESSION['answer']) && count($_SESSION['answer']) === 3) {
    $alleGoed = true;
    foreach ($juisteAntwoorden as $i => $juist) {
        if (!isset($_SESSION['answer'][$i]) || strtolower(trim($_SESSION['answer'][$i])) !== $juist) {
            $alleGoed = false;
            break;
        }
    }
}

if (!isset($_SESSION['starttijd'])) {
    $_SESSION['starttijd'] = time();
}

try {
  $stmt = $db_connection->prepare("SELECT * FROM questions WHERE roomId = 1");
  $stmt->execute();
  $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room 1</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <h1>Tijd over: <span id="timer">3:00</span></h1>
  <button id="startBtn">Begin het spel</button>
  <script src="timer.js"></script>

  <div id="img-container">
    <img id="tools-img" src="cel.jpg" usemap="#toolsmap" alt="Gereedschap afbeelding">
    <div id="highlight" class="highlight"></div>
  </div>

  <map name="toolsmap">
    <area shape="rect" coords="500,265,565,365" alt="hand" onclick="toonVraag(1)" href="javascript:void(0);">
    <area shape="rect" coords="736,214,773,277" alt="steen" onclick="toonVraag(2)" href="javascript:void(0);">
    <area shape="rect" coords="391,429,410,446" alt="tralie" onclick="toonVraag(3)" href="javascript:void(0);">
  </map>

  <div id="vraag-container" style="display: none;">
    <div id="vraagtekst"></div>
    <input type="text" id="antwoord" placeholder="Typ je antwoord hier...">
    <br>
    <button onclick="verwerkAntwoord()">Controleer</button>
    <div id="feedback"></div>
  </div>

  <!-- Verborgen vraagdata uit database -->
  <div id="vragen-data" style="display:none;">
    <?php foreach ($questions as $vraag): ?>
      <div class="vraag"
           data-id="<?= $vraag['id']; ?>"
           data-question="<?= htmlspecialchars(trim($vraag['question'])); ?>"
           data-answer="<?= htmlspecialchars(trim($vraag['answer'])); ?>"
           data-hint="<?= htmlspecialchars(trim($vraag['hint'] ?? '')); ?>">
      </div>
    <?php endforeach; ?>
  </div>

  <script>
    let vragenMap = {};
    document.querySelectorAll('#vragen-data .vraag').forEach(div => {
      const id = div.dataset.id;
      vragenMap[id] = {
        vraag: div.dataset.question,
        antwoord: div.dataset.answer,
        hint: div.dataset.hint
      };
    });

    let huidigeVraag = null;
    let score = 0;

    function toonVraag(nr) {
      huidigeVraag = nr;
      const vraag = vragenMap[nr];
      if (!vraag) return;

      document.getElementById("vraagtekst").innerText = vraag.vraag;
      document.getElementById("antwoord").value = "";
      document.getElementById("feedback").innerText = "";
      document.getElementById("vraag-container").style.display = "block";
    }

    function verwerkAntwoord() {
      if (!huidigeVraag || !vragenMap[huidigeVraag]) return;

      const gegeven = document.getElementById("antwoord").value.trim().toLowerCase();
      const juist = vragenMap[huidigeVraag].antwoord.trim().toLowerCase();

      if (gegeven === "") {
        document.getElementById("feedback").innerText = "⚠️ Vul een antwoord in!";
      } else if (gegeven === juist) {
        document.getElementById("feedback").innerText = "✅ Goed gedaan!";
        score++;
        // Controleer of de gebruiker 3 goede antwoorden heeft
        if (score >= 3) {
            window.location.href = 'win.php';
        }
      
        // Sla het goede antwoord op in de sessie via AJAX
        fetch('save_answer.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'vraag=' + encodeURIComponent(huidigeVraag) + '&antwoord=' + encodeURIComponent(gegeven)
        });
      } else {
        let hint = vragenMap[huidigeVraag].hint;
        document.getElementById("feedback").innerText = "❌ Niet correct." + (hint ? " Hint: " + hint : "");
      }
    }

    // Highlight-effect
    const gebieden = [
      {coords: [500,265,565,365]},
      {coords: [736,214,773,277]},
      {coords: [391,429,410,446]}
    ];

    document.querySelectorAll('area').forEach((area, idx) => {
      area.addEventListener('mouseover', function() {
        const highlight = document.getElementById('highlight');
        const coords = gebieden[idx].coords;
        highlight.style.left = coords[0] + "px";
        highlight.style.top = coords[1] + "px";
        highlight.style.width = (coords[2] - coords[0]) + "px";
        highlight.style.height = (coords[3] - coords[1]) + "px";
        highlight.style.display = "block";
      });
      area.addEventListener('mouseout', function() {
        document.getElementById('highlight').style.display = "none";
      });
    });

  </script>
  <script src="script.js"></script>
</body>
</html>
