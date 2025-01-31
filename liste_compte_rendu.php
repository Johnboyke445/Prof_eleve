<?php
session_start();  
include "_conf.php"; 

$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {  
    die("Erreur de connexion: " . mysqli_connect_error());  
}

// Requête pour récupérer tous les comptes rendus
$sql = "SELECT cr.*, user.nom AS nom_personne 
        FROM cr 
        LEFT JOIN user ON cr.id_user = user.id";

$stmt = mysqli_prepare($connexion, $sql);  
mysqli_stmt_execute($stmt);  
$result = mysqli_stmt_get_result($stmt);  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Comptes Rendus des Élèves</title>  
    <link rel="stylesheet" href="CR.ELEVES.css">  
</head>
<body>
    <div class="container"> 
        <h2>Comptes Rendus des Élèves</h2> 
        <form method="post" action="prof.php">
    <button type="submit" class="back-button">Retour</button>
</form><br>
        <table id="crTable">
            <thead>
                <tr>
                    <th>Nom de l'élève</th>
                    <th>Sujet</th>
                    <th>Contenu</th>
                    <th>Date du CR</th>
                    <th>Commentaires</th>
                    <th>Date de création</th>
                    <th>Date de modification</th>
                    <th>Vu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <form method="post" action="update_cr.php">
                        <!-- Champs cachés pour envoyer l'ID -->
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                        <td><?php echo htmlspecialchars($row['nom_personne'] ?? 'Inconnu'); ?></td>
                        <td><?php echo htmlspecialchars($row['sujet']); ?></td>
                        <td><?php echo htmlspecialchars($row['Contenu']); ?></td>
                        <td><?php echo htmlspecialchars($row['dateCR']); ?></td>
                        <td><input type="text" name="commentaire" value="<?php echo htmlspecialchars($row['commentaire']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['dateCreation']); ?></td>
                        <td><?php echo htmlspecialchars($row['dateModif']); ?></td>
                        <td><?php echo htmlspecialchars($row['Vu']); ?></td>
                        <td>
                            <button type="submit">Modifier</button>
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
