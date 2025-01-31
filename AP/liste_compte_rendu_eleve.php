<?php
session_start();
include "_conf.php"; 

 
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

$id_user = $_SESSION['id_user']; // Récupère l'ID de l'utilisateur connecté depuis la session

// Requête pour récupérer les comptes rendus de l'élève connecté
$sql = "SELECT cr.*, user.nom AS nom_personne 
        FROM cr 
        LEFT JOIN user ON cr.id_user = user.id 
        WHERE cr.id_user = ?"; // Limite la requête aux comptes rendus de l'élève connecté

$stmt = mysqli_prepare($connexion, $sql);  
mysqli_stmt_bind_param($stmt, "i", $id_user); // Lie l'ID de l'élève à la requête
mysqli_stmt_execute($stmt);  
$result = mysqli_stmt_get_result($stmt);  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Mes Comptes Rendus</title>  
    <link rel="stylesheet" href="CR.ELEVES.css">  
    <style>
        /* Style pour le bouton Supprimer */
        .delete-button {
            background-color: #ff4d4d; /* Rouge */
            color: white; /* Texte blanc */
            border: none; /* Pas de bordure */
            padding: 5px 10px; /* Espacement interne */
            cursor: pointer; /* Curseur en forme de main */
            margin-left: 10px; /* Espace à gauche */
        }

        /* Style pour le bouton Mettre à jour */
        .update-button {
            background-color: #4CAF50; /* Vert */
            color: white; /* Texte blanc */
            border: none; /* Pas de bordure */
            padding: 5px 10px; /* Espacement interne */
            cursor: pointer; /* Curseur en forme de main */
        }

        /* Style pour les boutons au survol */
        .delete-button:hover {
            background-color: #cc0000; /* Rouge plus foncé au survol */
        }

        .update-button:hover {
            background-color: #45a049; /* Vert plus foncé au survol */
        }

        /* Aligner les formulaires côte à côte */
        .action-form {
            display: inline-block; /* Afficher les formulaires en ligne */
            margin: 0; /* Supprimer les marges par défaut */
        }
    </style>
</head>
<body>
    <div class="container"> 
        <h2>Mes Comptes Rendus</h2> 
        <form method="post" action="eleve.php">
            <button type="submit" class="back-button">Retour</button>
        </form><br>

        <table id="crTable">
            <thead>
                <tr>
                    <th>Nom de la personne</th>
                    <th>Sujet</th>
                    <th>Contenu</th>
                    <th>Date du CR</th>
                    <th>Commentaires</th>
                    <th>Date de création</th>
                    <th>Date de modification</th>
                    <th>vu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <!-- Affichage des informations de chaque compte rendu -->
                    <td><?php echo htmlspecialchars($row['nom_personne'] ?? 'Inconnu'); ?></td>
                    <td><input type="text" name="sujet" value="<?php echo htmlspecialchars($row['sujet']); ?>"></td>
                    <td><input type="text" name="contenu" value="<?php echo htmlspecialchars($row['Contenu']); ?>"></td>
                    <td><?php echo htmlspecialchars($row['dateCR']); ?></td>
                    <td><?php echo htmlspecialchars($row['commentaire']); ?></td>
                    <td><?php echo htmlspecialchars($row['dateCreation']); ?></td>
                    <td><?php echo htmlspecialchars($row['dateModif']); ?></td>
                    <td><?php echo htmlspecialchars($row['Vu']); ?></td>
                    <td>
                        <!-- Formulaire pour la mise à jour -->
                        <form action="update_cr.php" method="post" class="action-form">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" class="update-button">Mettre à jour</button>
                        </form>

                        <!-- Formulaire pour la suppression -->
                        <form action="delete_cr.php" method="post" class="action-form">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte rendu ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php mysqli_close($connexion); ?>