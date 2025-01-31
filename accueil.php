<?php
session_start();
include "_conf.php";

if (isset($_POST['send_connexion'])) {
    $varlogin = $_POST['login']; 
    $varmdp =  $_POST['mdp']; 
    
    // Connexion à la base de données
    if ($connexion = mysqli_connect($serveurBDD, $userBDD, $mdpBDD, $nomBDD)) {
        // Recherche de l'utilisateur
        $requete = "SELECT * FROM user WHERE login=?";
        $stmt = mysqli_prepare($connexion, $requete);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $varlogin);
            mysqli_stmt_execute($stmt);
            $resultat = mysqli_stmt_get_result($stmt);

            if ($donnees = mysqli_fetch_assoc($resultat)) {
                if ($donnees['mdp'] == $varmdp) {
                    // Connexion réussie
                    $_SESSION['login'] = $varlogin;
                    $_SESSION['id_statut'] = $donnees['id_statut'];
                    $_SESSION['id_user'] = $donnees['id'];  // Stocke l'ID utilisateur dans la session
                
                    // Redirection en fonction du statut
                    if ($donnees['id_statut'] == 1) {
                        header('Location: prof.php'); // Page pour les professeurs
                    } elseif ($donnees['id_statut'] == 2) {
                        header('Location: eleve.php'); // Page pour les élèves
                    } elseif ($donnees['id_statut'] == 3) {
                        header('Location: ADMIN.php'); // Page pour un statut 3
                    } elseif ($donnees['id_statut'] == 4) {
                        header('Location: secretaire.php'); // Page pour un statut 4
                    } else {
                        echo "Statut non reconnu.";
                    }
                    exit;
                } else {
                    echo "Mot de passe incorrect.";
                }
            } else {
                echo "<hr> Connexion utilisateur échouée. Login introuvable.";
            }
        } else {
            echo "Erreur lors de la préparation de la requête.";
        }

        // Fermer la connexion
        mysqli_close($connexion);
    } else {
        echo 'Erreur de connexion à la base de données.';
    }
}
?>