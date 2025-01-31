<?php
session_start(); // Démarrer la session
include "_conf.php";

$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {  
    die("Erreur de connexion: " . mysqli_connect_error());  
}

// Récupérer le rôle de l'utilisateur depuis la session
$id_statut = $_SESSION['id_statut'] ?? null;

// Déterminer la page de retour en fonction du rôle de l'utilisateur
if ($id_statut == 3) {
    $page_retour = 'ADMIN.php'; // Admin
} elseif ($id_statut == 4) {
    $page_retour = 'secretaire.php'; // Secrétaire
}

// Requête pour compter les comptes rendus par utilisateur
$sql = 'SELECT user.nom, user.prenom, COUNT(*) AS nombre_comptes_rendus 
        FROM cr
        INNER JOIN user ON cr.id_user = user.id
        GROUP BY user.id;';
$result = mysqli_query($connexion, $sql);

if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . mysqli_error($connexion));
}

// Requête pour compter le total des comptes rendus
$total_cr_sql = 'SELECT COUNT(*) AS total_comptes_rendus FROM cr';
$total_cr_result = mysqli_query($connexion, $total_cr_sql);
$total_comptes_rendus = mysqli_fetch_assoc($total_cr_result)['total_comptes_rendus'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Nombre total de comptes rendus</title>  
    <link rel="stylesheet" href="CR.ELEVES.css">  
    <style>
        /* Style pour le bouton Retour */
        .back-button {
            background-color: #4CAF50; /* Vert */
            color: white; /* Texte blanc */
            border: none; /* Pas de bordure */
            padding: 10px 20px; /* Espacement interne */
            cursor: pointer; /* Curseur en forme de main */
            border-radius: 5px; /* Coins arrondis */
            margin-bottom: 20px; /* Espace en dessous */
        }

        .back-button:hover {
            background-color: #45a049; /* Vert plus foncé au survol */
        }
    </style>
</head>
<body>
    <div class="container"> 
        <!-- Bouton Retour dynamique -->
        <form method="post" action="<?php echo htmlspecialchars($page_retour); ?>">
            <button type="submit" class="back-button">Retour</button>
        </form>

        <h2>Nombre total de Comptes Rendus</h2> 

        <!-- Affichage du total des comptes rendus -->
        <p><strong>Total des Comptes Rendus : </strong><?php echo $total_comptes_rendus; ?></p>

        <!-- Tableau des comptes rendus par utilisateur -->
        <table id="crTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nombre de Comptes Rendus</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_comptes_rendus']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php 
// Fermeture de la connexion
mysqli_close($connexion); 
?>