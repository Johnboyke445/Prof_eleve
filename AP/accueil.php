<?php 
session_start();
include "_conf.php";

if (isset($_POST['send_connexion'])) {
    $varlogin = $_POST['login']; 
    $varmdp = $_POST['mdp']; 

    // Connexion à la base de données
    if($connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD)) {
        // Recherche de l'utilisateur
        $requete = "SELECT * FROM user WHERE login='$varlogin' AND mdp='$varmdp'";
        $resultat = mysqli_query($connexion, $requete);

        if ($donnees = mysqli_fetch_assoc($resultat)) { // Récupère la prochaine ligne de résultats comme tableau associatif
            $_SESSION['login'] = $varlogin;
            $_SESSION['mdp'] = $varmdp;
            $_SESSION['id_statut'] = $donnees['id_statut'];
            $_SESSION['id_user'] = $donnees['id'];  // Ajoutez cette ligne pour stocker id_user dans la session
            
            // Rediriger en fonction du statut
            if ($donnees['id_statut'] == 1) {
                header('Location: perso.php'); // Page pour les professeurs
            } elseif ($donnees['id_statut'] == 2) {
                header('Location: eleve.php'); // Page pour les élèves
            } else {
                echo "Statut non reconnu.";
            }
            exit;
        } else {
            echo "<hr> Connexion utilisateur échouée. Login/Mot de passe introuvable.";
        }

        // Fermer la connexion
        mysqli_close($connexion);
    } else {
        echo 'Erreur de connexion à la base de données';
    }
}
?>
