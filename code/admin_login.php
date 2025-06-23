<?php
session_start();

$admin_user = 'admin';
$admin_pass = '0000'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Ongeldige inloggegevens.";
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
    <title>Admin Login</title>
</head>
<body>
<div class="login-container">
<h2>Admin Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Gebruikersnaam: <input type="text" name="username"><br>
    Wachtwoord: <input type="password" name="password"><br>
    <button type="submit">Login</button>
    
</form>
</div>
</body>
</html>
