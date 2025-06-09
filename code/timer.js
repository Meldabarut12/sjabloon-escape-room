let tijdOver = 180; // standaard starttijd
let timerInterval;

// Reset timer als je op room_1.php komt (nieuwe spelstart)
if (window.location.pathname.includes('room_1.php')) {
  localStorage.removeItem('escape_tijdOver');
  tijdOver = 180;
}

const opgeslagenTijd = localStorage.getItem('escape_tijdOver');
if (opgeslagenTijd !== null) {
  tijdOver = parseInt(opgeslagenTijd, 10);
}

function updateTimer() {
  const minuten = Math.floor(tijdOver / 60);
  const seconden = tijdOver % 60;
  document.getElementById('timer').textContent =
    `${String(minuten).padStart(2, '0')}:${String(seconden).padStart(2, '0')}`;
  // Sla de resterende tijd op in localStorage
  localStorage.setItem('escape_tijdOver', tijdOver);
}

function startTimer() {
  if (timerInterval) return;
  timerInterval = setInterval(() => {
    tijdOver--;
    updateTimer();
    if (tijdOver < 0) {
      clearInterval(timerInterval);
      document.getElementById('timer').textContent = "Tijd is om!";
      if (typeof onTimeUp === "function") {
        onTimeUp();
      }
    }
  }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('startBtn').addEventListener('click', startTimer);
  updateTimer(); // toon begintijd bij laden

  // Start de timer automatisch als je in room_2.php bent (timer moet doorlopen)
  if (window.location.pathname.includes('room_2.php')) {
    startTimer();
    document.getElementById('startBtn').style.display = 'none'; // Verberg knop in room_2
  }
});