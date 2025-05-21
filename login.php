<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Gestion de Salles</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background: linear-gradient(to right, #2f3e46, #354f52);
    }
    .login-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      width: 400px;
    }
    .login-container h2 {
      text-align: center;
      color: #2f3e46;
      margin-bottom: 20px;
    }
    .login-container label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    .login-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .login-container button {
      width: 100%;
      margin-top: 20px;
      padding: 10px;
      background-color: #2f3e46;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .login-container button:hover {
      background-color: #52796f;
    }
    .links {
      text-align: center;
      margin-top: 15px;
    }
    .links a {
      color: #2f3e46;
      text-decoration: none;
      margin: 0 10px;
    }
    .links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Connexion</h2>
    <form action="dashboard.html" method="GET">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Se connecter</button>
    </form>
    <div class="links">
      <a href="mot-de-passe-oublie.html">Mot de passe oublié</a>
      <a href="creer-compte.html">Créer un compte</a>
    </div>
  </div>
</body>
</html>