let tijdOver = 180; // 3 minuten
let timerInterval;

// Reset timer bij openen van room_1.php
if (window.location.pathname.includes('room_1.php')) {
  localStorage.removeItem('escape_tijdOver');
  tijdOver = 180;
}

// Herstel tijd uit localStorage als aanwezig
const opgeslagenTijd = localStorage.getItem('escape_tijdOver');
if (opgeslagenTijd !== null) {
  tijdOver = parseInt(opgeslagenTijd, 10);
}

// Timer bijwerken op scherm + opslaan
function updateTimer() {
  const minuten = Math.floor(tijdOver / 60);
  const seconden = tijdOver % 60;
  document.getElementById('timer').textContent =
    `${String(minuten).padStart(2, '0')}:${String(seconden).padStart(2, '0')}`;
  localStorage.setItem('escape_tijdOver', tijdOver);
}

// Start de timer
function startTimer() {
  if (timerInterval) return; // voorkomt meerdere interval-starts
  timerInterval = setInterval(() => {
    tijdOver--;
    updateTimer();
    if (tijdOver < 0) {
      clearInterval(timerInterval);
      document.getElementById('timer').textContent = "Tijd is om!";
      if (typeof onTimeUp === "function") {
        onTimeUp(); // roept verlies-functie aan
      } else {
        window.location.href = "lose.php";
      }
    }
  }, 1000);
}

// Startknop activeren
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('startBtn').addEventListener('click', startTimer);
  updateTimer(); // toon actuele tijd direct bij laden
});
