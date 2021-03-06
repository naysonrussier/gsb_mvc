<?php

/**
 * Gestion des frais
 *
 * PHP Version 7.2
 *
 * @category  PPE
 * @package   GSB
 * @author    Nayson Russier <nayson.russier@harmony-group.fr>
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
// Clôture des fiches non clôturées
$moisEnCours = getMois(date('d/m/Y'));
$pdo->cloturerFicheFrais($moisEnCours);

$mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_STRING);
if (!$mois) {
    $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);
}
$idVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_STRING);
if (!$idVisiteur) {
    $idVisiteur = filter_input(INPUT_GET, 'visiteur', FILTER_SANITIZE_STRING);
}

$lesVisiteurs = $pdo->getLesVisiteurs();
if (!$idVisiteur) {
    $idVisiteur = $lesVisiteurs[0]['id'];
}

// Modification du mois en cours, suivant le visiteur
$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
    $mois = $pdo->dernierMoisSaisi($idVisiteur);
}

// Récupération infos fiches
$infoFiche = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
$etatFiche = $infoFiche['idEtat'];
$etatLibFiche = $infoFiche['libEtat'];

$estPost = $_SERVER['REQUEST_METHOD'] === 'POST';


// Si l'état de la fiche n'est pas clôturé, bloquer la saisie
if ($etatFiche != 'CL') {
    $action = '';
    ajouterErreur($etatLibFiche . ", il est ainsi impossible d'effectuer des modifications");
    include 'vues/v_erreurs.php';
}
switch ($action) {
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if ($estPost) {
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
                $succes = "Les frais ont bien été enregistrés";
                include 'vues/v_succes.php';
            } else {
                ajouterErreur('Les valeurs des frais doivent être numériques');
                include 'vues/v_erreurs.php';
            }
        }
        break;
    case 'validerMajFraisHorsForfait':
        if ($estPost) {
            $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
            $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
            $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            valideInfosFrais($dateFrais, $libelle, $montant);
            if (nbErreurs() != 0) {
                include 'vues/v_erreurs.php';
            } else {
                $pdo->majFraisHorsForfait(
                        $id,
                        $libelle,
                        $dateFrais,
                        $montant
                );
                $succes = "Le frais hors forfait \"" . $libelle . "\" d'un montant de " . $montant . " € a bien été validé.";
                include 'vues/v_succes.php';
            }
        }
        break;
    case 'refusFraisHorsFofrait':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_NUMBER_INT);

        // récupération et maj du frais actuel
        $frais = $pdo->getUnFraisHorsForfait($idFrais);
        $libelle = 'REFUSE : ' . $frais['libelle'];
        $date = $frais['date'];
        $montant = $frais['montant'];
        $pdo->majFraisHorsForfait($idFrais, $libelle, $date, $montant);

        $succes = 'Frais hors forfait refusé avec succés.';
        include 'vues/v_succes.php';
        break;
    case 'reporterFraisHorsFofrait':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_NUMBER_INT);

        // Création du mois suivant
        $moisSuivant = getMoisSuivant($mois);
        if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)) {
            $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
        }

        // récupération et maj du frais actuel
        $frais = $pdo->getUnFraisHorsForfait($idFrais);
        $libelle = $frais['libelle'];
        $date = $frais['date'];
        $montant = $frais['montant'];
        $pdo->creeNouveauFraisHorsForfait($idVisiteur, $moisSuivant, $libelle, $date, $montant);

        // suppression de l'ancien frais
        $pdo->supprimerFraisHorsForfait($idFrais);
        $succes = 'Frais hors forfait reporté avec succés.';
        include 'vues/v_succes.php';
        break;
    case 'validerFrais':
        $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_SANITIZE_NUMBER_INT);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $montant = 0;
        foreach ($lesFraisForfait as $unFraisForfait) {
            $montant += $unFraisForfait['montant'] * $unFraisForfait['quantite'];
        }
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            if (substr($unFraisHorsForfait['libelle'], 0, 6) != 'REFUSE') {
                $montant += $unFraisHorsForfait['montant'];
            }
        }
        echo $montant;
        $pdo->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs);
        $pdo->majMontantFicheFrais($idVisiteur, $mois, $montant);
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'VA');
        $succes = "La fiche a bien été validée.";
        include 'vues/v_succes.php';
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $mois);
include 'vues/compta/v_listeVisiteursMois.php';
require 'vues/compta/v_listeFraisForfait.php';
require 'vues/compta/v_listeFraisHorsForfait.php';
require 'vues/compta/v_nbjustificatifs.php';
