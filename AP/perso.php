
<?php
session_start();

// Vérifie si l'utilisateur n'est pas connecté ou n'est pas un professeur (id_statut != 1)
if (!isset($_SESSION['id_statut']) || $_SESSION['id_statut'] != 1) {
    // Redirection si l'utilisateur n'est pas un professeur
    header('Location: accueil.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface professeur</title>
    <link rel="stylesheet" href="eleve.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenue, professeur <?php echo $_SESSION['login']; ?></h2>
        <form method="post" action="liste_compte_rendu.php">
            <input type="submit" value="Liste compte rendus">
        </form>

        <form method="post" action="index.php">
            <input type="submit" value="Déconnexion">
        </form>
    </div>
</body>
</html>
  