<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="oubli.css">
    <title>Mot de Passe Oublié</title>
</head>
<body>
<div class="container"> <!-- Conteneur pour centrer le formulaire -->
        <h2>Réinitialisation de Mot de Passe</h2>
        <form action="oubli.php" method="post">
            <label for="email">Adresse Email :</label>
            <input type="email" name="email" id="email" required>
            <input type="submit" name="send_email" value="Envoyer">
        </form>
</body> 

<?php
session_start();

function passgen1($nbChar) {
    $chaine = "mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
    srand((double)microtime()*1000000);
    $pass = '';
    for($i = 0; $i < $nbChar; $i++){
        $pass .= $chaine[rand() % strlen($chaine)];
    }
    return $pass;
}

include "_conf.php"; // Inclusion de la configuration pour la connexion

if (isset($_POST['send_email']) && isset($_POST['email'])) {
    $varemail = trim($_POST['email']);
    echo "Email saisi : $varemail <br>";

    // Connexion à la base de données
    if ($connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD)) {

        // Requête pour chercher l'email
        $requete = "SELECT * FROM user WHERE LOWER(email) = LOWER(?)";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, 's', $varemail);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        if (!$resultat) {
            die("Erreur SQL : " . mysqli_error($connexion));
        }

        $trouve = mysqli_num_rows($resultat);
        
        if ($trouve == 1) {
            // Générer un nouveau mot de passe
            $mdpnew = passgen1(10);

            // Hachage du mot de passe avec password_hash
            $hashed_mdp = md5($mdpnew);

            // Mise à jour du mot de passe dans la base de données
            $update_sql = "UPDATE user SET mdp = ? WHERE email = ?";
            $stmt_update = mysqli_prepare($connexion, $update_sql);
            mysqli_stmt_bind_param($stmt_update, 'ss', $hashed_mdp, $varemail);
            mysqli_stmt_execute($stmt_update);

            // Préparation du message
            $message = "Bonjour, votre nouveau mot de passe est : $mdpnew";
            $message = wordwrap($message, 70);

            // Envoi de l'email
            if (mail($varemail, "Réinitialisation de mot de passe", $message)) {
                echo "Un email avec le nouveau mot de passe a été envoyé à : $varemail<br>";
            } else {
                echo "Échec de l'envoi de l'email.<br>";
            }

        } else {
            echo "Adresse email introuvable.<br>";
        }

        // Fermer la connexion
        mysqli_close($connexion);
    } else {
        echo "Erreur de connexion à la base de données.<br>";
    }
}
?>

</html>
