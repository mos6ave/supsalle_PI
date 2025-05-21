<?php
require 'config.php';

$message = '';
$email = $_GET['email'] ?? '';
$show_password_fields = false;

if (!$email) {
    header("Location: mot-de-passe-oublie.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp_input = $_POST['otp'] ?? '';
    $new_password = $_POST['new-password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    $stmt = $conn->prepare("SELECT otp_code, otp_expiration FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_code, $otp_expiration);
    $stmt->fetch();
    $stmt->close();

    $now = date("Y-m-d H:i:s");

    if ($otp_input === $otp_code && $now <= $otp_expiration) {
        $show_password_fields = true;

        if (!empty($new_password) && !empty($confirm_password)) {
            if (strlen($new_password) < 8) {
                $message = "Le mot de passe doit contenir au moins 8 caractères.";
            } elseif ($new_password !== $confirm_password) {
                $message = "Les mots de passe ne correspondent pas.";
            } else {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expiration = NULL WHERE email = ?");
                $stmt_update->bind_param("ss", $hashed, $email);
                $stmt_update->execute();
                $stmt_update->close();

                header("Location: login.php?reset=success");
                exit;
            }
        }
    } else {
        $message = "Code OTP invalide ou expiré.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialiser le mot de passe</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #2f3e46, #354f52);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      width: 400px;
    }
    h2 {
      text-align: center;
      color: #2f3e46;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .password-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 12px;
      cursor: pointer;
    }
    button {
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      background: #2f3e46;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
    }
    button:hover{
      background-color :#181818;
    }
    .error {
      color: red;
      margin-top: 10px;
      text-align: center;
    }
    #timer {
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Réinitialiser le mot de passe</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form method="POST">
      <?php if (!$show_password_fields): ?>
        <label for="otp">Code OTP</label>
        <input type="text" name="otp" id="otp" maxlength="6" required autofocus>
        <p id="timer">Vous pouvez renvoyer un code dans <span id="countdown">60</span> secondes.</p>
        <button type="button" id="resendBtn" onclick="resendOTP()" style="display:none;">Renvoyer le code</button>
      <?php endif; ?>

      <?php if ($show_password_fields): ?>
        <input type="hidden" name="otp" value="<?= htmlspecialchars($otp_input) ?>">
        <label for="new-password">Nouveau mot de passe</label>
        <div class="password-container">
          <input type="password" name="new-password" id="new-password" required>
          <span class="toggle-password" onclick="togglePassword('new-password')">👁️</span>
        </div>

        <label for="confirm-password">Confirmer le mot de passe</label>
        <div class="password-container">
          <input type="password" name="confirm-password" id="confirm-password" required>
          <span class="toggle-password" onclick="togglePassword('confirm-password')">👁️</span>
        </div>
      <?php endif; ?>

      <button type="submit"><?= $show_password_fields ? 'Réinitialiser' : 'Vérifier le code' ?></button>
    </form>
    <br><a href="mot-de-passe-oublie.php">← Retour</a>
  </div>

  <script>
    function togglePassword(id) {
      var field = document.getElementById(id);
      field.type = field.type === 'password' ? 'text' : 'password';
    }

    <?php if (!$show_password_fields): ?>
    let seconds = 60;
    const countdown = document.getElementById("countdown");
    const resendBtn = document.getElementById("resendBtn");

    const timer = setInterval(() => {
      seconds--;
      countdown.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(timer);
        document.getElementById("timer").style.display = "none";
        resendBtn.style.display = "inline";
      }
    }, 1000);

    function resendOTP() {
      fetch("resend-otp.php?email=<?= urlencode($email) ?>")
        .then(res => res.text())
        .then(msg => alert(msg));
    }
    <?php endif; ?>
  </script>
</body>
</html>
