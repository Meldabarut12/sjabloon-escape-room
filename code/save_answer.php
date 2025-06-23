<?php
// filepath: c:\xampp2\htdocs\code\save_answer.php
session_start();

if (isset($_POST['vraag']) && isset($_POST['antwoord'])) {
    $vraag = intval($_POST['vraag']) - 1; // 0-based index
    $antwoord = trim($_POST['antwoord']);
    $_SESSION['answer'][$vraag] = $antwoord;
    echo "ok";
} else {
    echo "error";
}