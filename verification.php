<?php
require 'config.php';

// Récupérer l'email depuis l'URL (GET)
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    // Si l'email n'est pas défini, rediriger vers la page de création de compte
    header("Location: creer-compte.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp_input = $_POST['otp'];

    // Préparer la requête pour récupérer le code OTP et sa date d'expiration
    $stmt = $conn->prepare("SELECT otp_code, otp_expiration FROM users WHERE email = ? AND is_verified = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp_code, $otp_expiration);
    $stmt->fetch();
    $stmt->close();
    if ($otp_code) {
        $now = date("Y-m-d H:i:s");
        if ($otp_input == $otp_code) {
            if ($now <= $otp_expiration) {
                // Mettre à jour la vérification de l'utilisateur
                $stmt_update = $conn->prepare("UPDATE users SET is_verified = 1, otp_code = NULL, otp_expiration = NULL WHERE email = ?");
                $stmt_update->bind_param("s", $email);
                $stmt_update->execute();
                $stmt_update->close();

                // Rediriger vers la page de connexion avec un paramètre indiquant la vérification réussie
                header("Location: login.php?verified=1");
                exit;
            } else {
                $message = "Le code OTP a expiré.";
            }
        } else {
            $message = "Code OTP invalide.";
        }
    } else {
        $message = "Aucun code OTP trouvé ou compte déjà vérifié.";
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vérification OTP</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(to right, #2f3e46, #354f52);
      margin: 0;
    }
    .otp-container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
    }
    h2 {
      color: #2f3e46;
    }
    input {
     width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      margin-top: 20px;
      padding: 10px;
      width: 100%;
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
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="otp-container">
    <h2>Entrez votre code OTP</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form method="POST" action="">
      <input type="text" name="otp" maxlength="6" required autofocus>
      <button type="submit">Vérifier</button>
    </form>
    <p id="timer">Vous pouvez renvoyer un code dans <span id="countdown">60</span> secondes.</p>
<button type="button" id="resendBtn" onclick="resendOTP()" style="display:none;">Renvoyer le code</button>
<br><br>
<a href="creer-compte.php">← Retour</a>

<script>
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
    const email = new URLSearchParams(window.location.search).get("email");
    if (email) {
      fetch("resend-otp.php?email=" + encodeURIComponent(email))
        .then(response => response.text())
        .then(data => alert(data));
    }
  }
</script>

  </div>
</body>
</html>
