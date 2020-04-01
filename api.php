<?php

/**
 * Index du projet GSB
 *
 * PHP Version 7.2
 *
 * @category  PPE
 * @package   GSB
 * @author    Nayson Russier <nayson.russier@harmony-group.fr>
 * @copyright 2020 Nayson Russier
 * @version   GIT: <0>
 */
require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';

$pdo = PdoGsb::getPdoGsb();
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$donnees = filter_input(INPUT_POST, 'lesdonnees');
$donnees = json_decode($donnees);
switch ($action) {
    case 'connexion':
        $login = $donnees[0];
        $mdp = $donnees[1];

        $utilisateur = $pdo->apiConnexion($login, $mdp);
        if ($utilisateur["token"] == "") {
            echo "connexion_erreur%erreur";
        } else {
            $token = $utilisateur["token"];
            $nom = $utilisateur["nom"];
            $prenom = $utilisateur["prenom"];
            echo "connexion_succes%" . $token . "%" . $nom . "%" . $prenom;
        }
        break;
    case 'synchro':
        $token = $donnees[0];
        $mois = strval($donnees[1]);
        $lesFrais = (array)$donnees[2];
        $lesFraisHorsForfait = (array)$donnees[3];
        $visiteur = $pdo->apiGetUtilisateurParToken($token);
        if (is_array($visiteur)) {
            if ($pdo->estPremierFraisMois($visiteur['id'], $mois)) {
                $pdo->creeNouvellesLignesFrais($visiteur['id'], $mois);
            }
            $ficheFrais = $pdo->getLesInfosFicheFrais($visiteur['id'], $mois);
            $ficheFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteur['id'], $mois);
            if ($ficheFrais['idEtat'] != 'CR') {
                echo "synchro_erreur%" . $mois . "%La fiche n'est pas en cours de saisie ("
                . $ficheFrais['libEtat'] . ')';
            } else {
                // Traitement des frais forfait
                $pdo->majFraisForfait($visiteur['id'], $mois, $lesFrais);
                // Traitement des frais Hors forfait
                foreach ($ficheFraisHorsForfait as $unFraisHorsForfait) {
                    $pdo->supprimerFraisHorsForfait($unFraisHorsForfait['id']);
                }
                foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                    $unFraisHorsForfait = (array)$unFraisHorsForfait;
                    $libelle = $unFraisHorsForfait['motif'];
                    $date = date_create_from_format(
                            "Ymj", 
                            $mois . $unFraisHorsForfait['jour']
                    );
                    $date = $date->format("d/m/Y");
                    $montant = $unFraisHorsForfait['montant'];
                    $pdo->creeNouveauFraisHorsForfait(
                            $visiteur['id'],
                            $mois,
                            $libelle,
                            $date,
                            $montant);
                }
                echo "synchro_succes%" . $mois . "%OK";
            }
        } else {
            echo "synchro_erreur%Erreur d'identification";
        }
        break;
    default:
        echo '';
}