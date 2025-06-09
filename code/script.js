let questions = [];
let currentIndex = 0;
let correctCount = 0;

// Verzamel alle vragen en antwoorden uit de boxen
window.onload = function () {
  const boxes = document.querySelectorAll('.box');
  boxes.forEach(box => {
    questions.push({
      question: box.getAttribute('data-question'),
      answer: box.getAttribute('data-answer')
    });
    box.style.display = "none"; // Verberg de boxen
  });
  if (questions.length > 0) {
    showQuestion(0);
  }
};

function showQuestion(index) {
  document.getElementById('question').textContent = questions[index].question;
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
  document.getElementById('feedback').textContent = '';
  document.getElementById('answer').value = '';
  document.getElementById('answer').focus();
}

function closeModal() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';
}

function checkAnswer() {
  const userAnswer = document.getElementById('answer').value.trim().toLowerCase();
  const correctAnswer = questions[currentIndex].answer.trim().toLowerCase();
  if (userAnswer === correctAnswer) {
    correctCount++;
    document.getElementById('feedback').textContent = "Goed gedaan!";
    document.getElementById('feedback').style.color = "green";
    setTimeout(() => {
      currentIndex++;
      if (currentIndex < questions.length) {
        showQuestion(currentIndex);
      } else {
        finishScreen();
      }
    }, 800);
  } else {
    document.getElementById('feedback').textContent = "Helaas, probeer opnieuw.";
    document.getElementById('feedback').style.color = "red";
    document.getElementById('answer').value = '';
    document.getElementById('answer').focus();
  }
}

function finishScreen() {
  closeModal();
  const finishDiv = document.createElement('div');
  finishDiv.id = 'finish';
  finishDiv.style.position = "fixed";
  finishDiv.style.top = "50%";
  finishDiv.style.left = "50%";
  finishDiv.style.transform = "translate(-50%, -50%)";
  finishDiv.style.background = "#fff";
  finishDiv.style.padding = "40px";
  finishDiv.style.borderRadius = "10px";
  finishDiv.style.boxShadow = "0 0 20px rgba(0,0,0,0.2)";
  finishDiv.style.textAlign = "center";
  finishDiv.style.color = "#222";

  // Check op welke kamer je bent
  if (window.location.pathname.includes('room_1.php')) {
    finishDiv.innerHTML = `<h2>Gefeliciteerd!</h2><p>Je bent gepromoveerd naar kamer 2.<br>Je wordt doorgestuurd...</p>`;
    document.body.appendChild(finishDiv);
    setTimeout(() => {
      window.location.href = "room_2.php";
    }, 3000);
  } else if (window.location.pathname.includes('room_2.php')) {
    finishDiv.innerHTML = `<h2>Gefeliciteerd!</h2><p>Je hebt kamer 2 uitgespeeld.<br>Je wordt doorgestuurd...</p>`;
    document.body.appendChild(finishDiv);
    setTimeout(() => {
      window.location.href = "win.php";
    }, 3000);
  }
}

// Functie die wordt aangeroepen als de tijd op is
function onTimeUp() {
  closeModal();
  window.location.href = "lose.php";
}

// Enter toets activeert checkAnswer
document.getElementById('answer').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    checkAnswer();
  }
});

document.getElementById('naamForm').addEventListener('submit', function() {
  localStorage.setItem('starttijd', Date.now());
});