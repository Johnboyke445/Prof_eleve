<?php
session_start();  
include "_conf.php"; 

// Connexion à la base de données
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {  
    die("Erreur de connexion: " . mysqli_connect_error());  
}

// Message de statut
$message = '';

// Vérifier si le formulaire a été soumis en POST pour insérer dans la base de données
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Récupérer les valeurs envoyées par le formulaire
    $sujet = $_POST['sujet'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    $dateCR = date('Y-m-d'); 
    $commentaire = $_POST['commentaire'] ?? '';
    $dateCreation = date('Y-m-d ');   
    $dateModif = date('Y-m-d H:i:s');      
    $vu = 0;   
    $id_user = $_SESSION['id_user'] ?? 1;

    // Préparer la requête pour l'insertion
    $sql = "INSERT INTO `cr`(`sujet`, `Contenu`, `dateCR`, `commentaire`, `dateCreation`, `dateModif`, `Vu`, `id_user`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($connexion, $sql);  
    mysqli_stmt_bind_param($stmt, 'ssssssii', $sujet, $contenu, $dateCR, $commentaire, $dateCreation, $dateModif, $vu, $id_user);

    // Exécuter la requête
    if (mysqli_stmt_execute($stmt)) {
        $message = "Le compte rendu a été ajouté avec succès!";
    } else {
        $message = "Erreur lors de l'ajout du compte rendu : " . mysqli_error($connexion);
    }
    
    mysqli_stmt_close($stmt); // Fermer la requête
}

mysqli_close($connexion); // Fermer la connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte Rendu</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"],
        input[type="date"],
        textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .button {
            margin-top: 20px;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
              /* Styles CSS pour le bouton de retour */
              .back-button {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff; /* Couleur bleue pour le retour */
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Prend toute la largeur du formulaire */
        }
        .back-button:hover {
            background-color: #0056b3; /* Couleur bleu foncé au survol */
        }
        .back-button:focus {
            outline: none; /* Enlève l'effet de focus par défaut */
        }


     
    </style>
</head>
<body>
    <div class="container">
        <h2>Créer un Nouveau Compte Rendu</h2>

    <?php echo $message; ?>

        <!-- Formulaire avec valeurs par défaut -->
        <form action="" method="POST">
            <label for="sujet">Sujet :</label>
            <input type="text" id="sujet" name="sujet" value="Sujet par défaut" required>

            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" required>Contenu par défaut</textarea>

            <label for="dateCR">Date du Compte Rendu :</label>
            <input type="date" id="dateCR" name="dateCR" required>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="commentaire">Commentaire par défaut</textarea>

            <button type="submit" name="submit" class="button">Ajouter le Compte Rendu</button>
           
        </form>

        <form method="post" action="eleve.php">
    <button type="submit" class="back-button">Retour</button>
</form>


    </div>
</body>
</html>
