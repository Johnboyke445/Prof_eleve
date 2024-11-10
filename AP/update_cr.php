<?php
include "_conf.php";

// Connexion à la base de données
$connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD);
if (!$connexion) {
    die("Erreur de connexion: " . mysqli_connect_error());
}

// Récupérer les données envoyées en POST
$id = $_POST['id'];
$sujet = mysqli_real_escape_string($connexion, $_POST['sujet']);
$contenu = mysqli_real_escape_string($connexion, $_POST['contenu']);
$commentaire = mysqli_real_escape_string($connexion, $_POST['commentaire']);

// Mettre à jour la base de données
$update_sql = "UPDATE cr SET sujet='$sujet', Contenu='$contenu', commentaire='$commentaire', dateModif=NOW() WHERE id='$id'";
if (mysqli_query($connexion, $update_sql)) {
    // Envoie la date de modification mise à jour en réponse
    echo "Mise à jour réussie. Date de modification : " . date("Y-m-d H:i:s");
} else {
    echo mysqli_error($connexion);
}

// Fermer la connexion
mysqli_close($connexion);
?>
