<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['email']) || empty($_GET['email'])) {
    exit("Email non fourni.");
}

$email = $_GET['email'];

$stmt = $conn->prepare("SELECT fullname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($fullname);
$stmt->fetch();
$stmt->close();

$otp = random_int(100000, 999999);
$expiration = date("Y-m-d H:i:s", strtotime('+10 minutes'));

$stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiration = ? WHERE email = ?");
$stmt->bind_param("sss", $otp, $expiration, $email);
$stmt->execute();
$stmt->close();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gestion.de.salle.sup@gmail.com';
    $mail->Password = 'gwxt tqhd gphl folg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('gestion.de.salle.sup@gmail.com', 'Vérification');
    $mail->addAddress($email, $fullname ?: $email);

    $mail->isHTML(true);
    $mail->Subject = 'Code de vérification (renvoyé)';
    $mail->Body = "Bonjour " . htmlspecialchars($fullname ?: 'utilisateur') . ",<br><br>
    Voici votre nouveau code de vérification : <b>$otp</b><br>Ce code expirera dans 10 minutes.";

    $mail->send();
    echo "Nouveau code envoyé avec succès.";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du code : " . $mail->ErrorInfo;
}
?>
