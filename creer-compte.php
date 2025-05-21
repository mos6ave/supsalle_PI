<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer un compte - Gestion de Salles</title>
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
    .signup-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      width: 400px;
    }
    .signup-container h2 {
      text-align: center;
      color: #2f3e46;
      margin-bottom: 20px;
    }
    .signup-container label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    .signup-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .signup-container button {
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
    .signup-container button:hover {
      background-color: #52796f;
    }
    .links {
      text-align: center;
      margin-top: 15px;
    }
    .links a {
      color: #2f3e46;
      text-decoration: none;
    }
    .links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <h2>Créer un compte</h2>
    <form action="dashboard.html" method="GET">
      <label for="fullname">Nom complet</label>
      <input type="text" id="fullname" name="fullname" required>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
      <label for="confirm-password">Confirmer le mot de passe</label>
      <input type="password" id="confirm-password" name="confirm-password" required>
      <button type="submit">S'inscrire</button>
    </form>
    <div class="links">
      <a href="login.html">Vous avez déjà un compte? Connectez-vous</a>
    </div>
  </div>
</body>
</html>