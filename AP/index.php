<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="coco3.css"> <!-- Lien vers le fichier CSS pour le style -->
</head>
<body>
    <div class="container"> <!-- Conteneur pour centrer le formulaire -->
        <h2>Se connecter</h2>

        <form method="post" action="accueil.php"> <!-- Formulaire de connexion -->
            <input type="text" name="login" id="login" placeholder="Login" required> <!-- Champ pour le login -->
            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required> <!-- Champ pour le mot de passe -->
            <div class="remember-me"> <!-- Case à cocher pour se souvenir de l'utilisateur -->
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Souviens-toi de moi</label>
            </div>
            <br>

            <input type="submit" value="Se connecter" name="send_connexion" class="btn-connect"> <!-- Bouton de soumission -->
        </form>

        <div class="forgot-password">  
            <form method="post" action="oubli.php"> <!-- Formulaire pour le mot de passe oublié -->
                <input type="submit" value="Mot de passe oublié ?" name="send_email" class="btn-forgot">
            </form>
        </div>
        <br>
    </div>
</body>
</html>
