<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $otp = rand(100000, 999999);
        $expiration = date("Y-m-d H:i:s", strtotime('+10 minutes'));

        $stmt_update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiration = ? WHERE email = ?");
        $stmt_update->bind_param("sss", $otp, $expiration, $email);
        $stmt_update->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gestion.de.salle.sup@gmail.com';
            $mail->Password = 'gwxt tqhd gphl folg'; // mot de passe d'application genere 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('gestion.de.salle.sup@gmail.com', 'Gestion de Salles');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Code OTP pour reinitialisation';
            $mail->Body = "Bonjour,<br><br>Votre code OTP pour réinitialiser votre mot de passe est : <b>$otp</b>.<br>Il est valable 10 minutes.";

            $mail->send();

            header("Location: reset-password.php?email=" . urlencode($email));
            exit;

        } catch (Exception $e) {
            $message = "Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo;
        }

    } else {
        $message = "Email non trouvé.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mot de passe oublié</title>
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
    .forgot-container {
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
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 15px;
      font-size: 16px;
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
  <div class="forgot-container">
    <h2>Mot de passe oublié</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Entrez votre email" required>
      <button type="submit">Envoyer le code OTP</button>
    </form>
    <a href="login.php">← Retour</a>
  </div>
</body>
</html>
