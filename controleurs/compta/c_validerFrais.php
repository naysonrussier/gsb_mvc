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

$mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_STRING);
$idVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_STRING);

$lesVisiteurs = $pdo->getLesVisiteurs();
if (!$idVisiteur) {
    $idVisiteur = $lesVisiteurs[0]['id'];
}

// Modification du mois en cours, suivant l
$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
    $mois = $pdo->dernierMoisSaisi($idVisiteur);
}

include 'vues/compta/v_listeVisiteursMois.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            $succes = "Les frais ont bien été enregistrés";
            include 'vues/v_succes.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;
    case 'validerMajFraisHorsForfait':

        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        valideInfosFrais($dateFrais, $libelle, $montant);
        if (nbErreurs() != 0) {
            include 'vues/v_erreurs.php';
        } else {
            $pdo->modifierFraisHorsForfait(
                $id,
                $libelle,
                $dateFrais,
                $montant
            );
            $succes = "Le frais hors forfait \"" . $libelle . "\" d'un montant de ". $montant ." € a bien été validé.";
            include 'vues/v_succes.php';
        }
        break;
    case 'validerMajNbJustificatifs':
        $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_SANITIZE_NUMBER_INT);
        $pdo->majNbJustificatifs($idVisiteur,$mois, $nbJustificatifs);
        $succes = "La fiche a bien été validée.";
        include 'vues/v_succes.php';
    }
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $mois);
require 'vues/compta/v_listeFraisForfait.php';
require 'vues/compta/v_listeFraisHorsForfait.php';
require 'vues/compta/v_nbjustificatifs.php';