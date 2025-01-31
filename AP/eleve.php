<?php
session_start();

// Vérifie si l'utilisateur n'est pas connecté ou n'est pas un eleve (id_statut != 1)
if (!isset($_SESSION['id_statut']) || $_SESSION['id_statut'] != 2) {
    // Redirection si l'utilisateur n'est pas un élève
    header('Location: accueil.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deconnexion'])) {
    // Détruire la session
    session_start();
    session_unset();
    session_destroy();

     
    header('Location: index.php?message=deconnexion_reussie');
    exit();  
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Élève</title>
    <link rel="stylesheet" href="eleve1.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenue, élève <?php echo $_SESSION['login']; ?></h2>
        <form method="post" action="liste_compte_rendu_eleve.php">
            <input type="submit" value="Liste compte rendus">
        </form>

        <form method="post" action="Créationdecr.php">
            <input type="submit" value="Créer un compte rendus">
        </form>

        <form method="post">
            <input type="submit" name="deconnexion" value="Déconnexion"> 
        </form>
    </div>
</body>
</html>
  