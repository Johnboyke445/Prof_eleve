<?php
session_start();  // Démarrer la session pour pouvoir utiliser $_SESSION

include "_conf.php";  // Inclure la configuration de la base de données

// Connexion à la base de données
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

// Vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['id_user'])) {
    die("Erreur : Utilisateur non connecté.");
}

// Récupérer les données envoyées en POST avec une vérification si elles existent
$id = isset($_POST['id']) ? $_POST['id'] : '';  // ID du compte rendu à mettre à jour
$sujet = isset($_POST['sujet']) ? mysqli_real_escape_string($connexion, $_POST['sujet']) : '';  // Sujet
$contenu = isset($_POST['contenu']) ? mysqli_real_escape_string($connexion, $_POST['contenu']) : '';  // Contenu

// Récupérer le champ 'commentaire' seulement si l'utilisateur est un professeur
$commentaire = isset($_POST['commentaire']) ? mysqli_real_escape_string($connexion, $_POST['commentaire']) : '';

// Récupérer l'id de l'utilisateur connecté
$id_user = $_SESSION['id_user']; 

// Vérifier le statut de l'utilisateur (professeur ou élève)
$sql_statut = "SELECT id_statut FROM user WHERE id = '$id_user'";
$result_statut = mysqli_query($connexion, $sql_statut);
$user_statut = mysqli_fetch_assoc($result_statut)['id_statut'];

// Initialiser la variable de message
$message = "";

// Vérifier quel type d'utilisateur est connecté
if ($user_statut == 1) {  // Professeur (id_statut = 1)
    // Le professeur ne peut mettre à jour que le commentaire
    if (empty($commentaire)) {
        $message = "Le commentaire ne peut pas être vide.";
    } else {
        $update_sql = "UPDATE cr SET commentaire='$commentaire', dateModif=NOW() WHERE id='$id'";
        if (mysqli_query($connexion, $update_sql)) {
            $message = "Commentaire mis à jour avec succès.";
        } else {
            $message = "Erreur de mise à jour : " . mysqli_error($connexion);
        }
    }
} elseif ($user_statut == 2) {  // Élève (id_statut = 2)
    // L'élève ne peut mettre à jour que le sujet et le contenu
    if (empty($sujet) || empty($contenu)) {
        $message = "Le sujet et le contenu ne peuvent pas être vides.";
    } else {
        $update_sql = "UPDATE cr SET sujet='$sujet', contenu='$contenu', dateModif=NOW() WHERE id='$id'";
        if (mysqli_query($connexion, $update_sql)) {
            $message = "Sujet et contenu mis à jour avec succès.";
        } else {
            $message = "Erreur de mise à jour : " . mysqli_error($connexion);
        }
    }
} else {
    $message = "Statut utilisateur non reconnu.";
}

// Fermer la connexion
mysqli_close($connexion);

// Afficher le message final
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous de lier votre fichier CSS ici -->
    <title>Résultat de la Mise à Jour</title>
</head>
<body>
    <div class="container">
        <h2>Résultat de la mise à jour</h2>
        <p><?php echo $message; ?></p>
        <form method="post" action="<?php echo ($_SESSION['id_statut'] == 1) ? 'prof.php' : 'eleve.php'; ?>">
            <button type="submit" class="back-button">Retour</button>
        </form>
    </div>
 


 
    <style>
        /* Styles généraux */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
    text-align: center;
}

h2 {
    color: #4CAF50;
}

p {
    font-size: 16px;
    color: #333;
}

.message {
    margin-top: 20px;
    padding: 15px;
    border-radius: 5px;
    background-color: #f8f9fa;
    border: 1px solid #d6d8db;
}

.success {
    color: #28a745;
    font-weight: bold;
}

/* Bouton Retour */
.back-button {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff; /* Bleu pour le bouton */
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    display: inline-block;
    text-decoration: none;
}

.back-button:hover {
    background-color: #0056b3; /* Bleu plus foncé au survol */
}

.back-button:focus {
    outline: none;
}

    </style>
</body>
</html>
