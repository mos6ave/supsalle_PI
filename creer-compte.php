<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm-password'];

    // Validation mot de passe minimum 8 caractères
    if (strlen($password) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";
    } else if ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // --- Vérification si email existe déjà ---
        $stmt_check = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Email déjà utilisé
            $message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
            $stmt_check->close();
        } else {
            $stmt_check->close();

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $otp = rand(100000, 999999);
            $expiration = date("Y-m-d H:i:s", strtotime('+10 minutes'));

            // Insertion avec OTP et is_verified = 0
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, otp_code, otp_expiration, is_verified) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("sssss", $fullname, $email, $hashed, $otp, $expiration);

            if ($stmt->execute()) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'gestion.de.salle.sup@gmail.com'; // Ton email
                    $mail->Password = 'gwxt tqhd gphl folg';           // Ton App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('gestion.de.salle.sup@gmail.com', 'Gestion de Salles');
                    $mail->addAddress($email, $fullname);
                    $mail->isHTML(true);
                    $mail->Subject = 'Code de verification';
                    $mail->Body = "Bonjour $fullname,<br><br>Voici votre code de vérification : <b>$otp</b><br>Ce code expirera dans 10 minutes.";

                    $mail->send();

                    // Redirection vers page vérification OTP avec email en GET
                    header("Location: verification.php?email=" . urlencode($email));
                    exit;
                } catch (Exception $e) {
                    $message = "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
                }
            } else {
                $message = "Erreur lors de la création du compte.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un compte</title>
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
    .signup-container {
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
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .password-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: -17px;
      top: 8px;
      cursor: pointer;
    }
    button {
      width: 100%;
      margin-top: 20px;
      padding: 10px;
      background: #2f3e46;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
    }
    button:hover{
      background-color :#181818;
    }
    .links {
      text-align: center;
      margin-top: 15px;
    }
    .links a {
      text-decoration: none;
      color: #2f3e46;
    }
    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <h2>Créer un compte</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form action="creer-compte.php" method="POST">
      <label for="fullname">Nom complet</label>
      <input type="text" name="fullname" id="fullname" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Mot de passe (min 8 caractères)</label>
      <div class="password-container">
        <input type="password" name="password" id="password" required minlength="8">
        <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
      </div>

      <label for="confirm-password">Confirmer le mot de passe</label>
      <div class="password-container">
        <input type="password" name="confirm-password" id="confirm-password" required minlength="8">
        <span class="toggle-password" onclick="togglePassword('confirm-password')">👁️</span>
      </div>

      <button type="submit">S'inscrire</button>
    </form>
    <div class="links">
      <a href="login.php">Connexion</a>
    </div>
  </div>

  <script>
    function togglePassword(id) {
      var input = document.getElementById(id);
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
