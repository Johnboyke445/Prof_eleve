<?php
session_start();
include "_conf.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    die("Accès non autorisé.");
}

// Connexion à la base de données
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

// Vérifier si l'ID du compte rendu est présent
if (isset($_POST['id'])) {
    $id_cr = intval($_POST['id']); // Sécuriser l'ID

    // Requête de suppression
    $sql = "DELETE FROM cr WHERE id = ? AND id_user = ?"; // Supprimer uniquement les CR de l'utilisateur connecté
    $stmt = mysqli_prepare($connexion, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $id_cr, $_SESSION['id_user']);
        mysqli_stmt_execute($stmt);

        // Vérifier si la suppression a réussi
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Le compte rendu a été supprimé avec succès.";
        } else {
            $message = "Erreur : Le compte rendu n'a pas pu être supprimé ou n'existe pas.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Erreur lors de la préparation de la requête.";
    }
} else {
    $message = "ID du compte rendu non spécifié.";
}

// Fermer la connexion
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression du compte rendu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .message-box h2 {
            margin-top: 0;
        }
        .message-box button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        .message-box button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Résultat de la suppression</h2>
        <p><?php echo $message; ?></p>
        <button onclick="window.location.href='liste_compte_rendu_eleve.php'">Retour</button>
    </div>
</body>
</html>