<?php
require 'config.php';
//zdt session
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
//houn zdt vih role
    $stmt = $conn->prepare("SELECT  password, id, is_verified,role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
      //zdt role houn mlli
        $stmt->bind_result($hashed, $user_id, $is_verified, $role);
        $stmt->fetch();

        if (!$is_verified) {
            $message = "Veuillez vérifier votre compte avant de vous connecter.";
        } else if (password_verify($password, $hashed)) {
          //dyrthe 2ne ramle ,ndour nkrd bihe email w role l'utlusateur wla adminsrateurr
          $_SESSION['user_id']=$user_id;
          $_SESSION['email'] = $email;  
          $_SESSION['role']=$role;
          if($role==='admin'){
            header("location:adminstrateur_salles.php");
          }else{
            header("Location:accueil.php");
          }
            exit;
        } else {
            $message = "Mot de passe incorrect.";
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
  <title>Connexion</title>
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
    .login-container {
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
      margin: 0 10px;
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
  <div class="login-container">
    <h2>Connexion</h2>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form action="login.php" method="POST">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Mot de passe</label>
      <div class="password-container">
        <input type="password" name="password" id="password" required>
        <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
      </div>

      <button type="submit">Se connecter</button>
    </form>
    <div class="links">
      <a href="mot-de-passe-oublie.php">Mot de passe oublié</a>
      <a href="creer-compte.php">Créer un compte</a>
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
