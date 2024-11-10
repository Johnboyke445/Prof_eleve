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
</head>
<body>
    <div class="container"> 
        <h2>Mes Comptes Rendus</h2> 
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <form action="update_cr.php" method="post">
                        <!-- Champs cachés pour envoyer l'ID du compte rendu -->
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                        <!-- Affichage des informations de chaque compte rendu -->
                        <td><?php echo htmlspecialchars($row['nom_personne'] ?? 'Inconnu'); ?></td>
                        <td><input type="text" name="sujet" value="<?php echo htmlspecialchars($row['sujet']); ?>"></td>
                        <td><input type="text" name="contenu"  value="<?php echo htmlspecialchars($row['Contenu']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['dateCR']); ?></td>
                        <td><input type="text" name="commentaire" value="<?php echo htmlspecialchars($row['commentaire']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['dateCreation']); ?></td>
                        <td><?php echo htmlspecialchars($row['dateModif']); ?></td>
                        <td>
                            <button type="submit">Mettre à jour</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php mysqli_close($connexion); ?>
