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

if (!$idVisiteur && !$mois) {
    $action = "listeFiches";
}

switch ($action) {
    case 'listeFiches':
        $lesFiches = $pdo->getFichesFraisValidees();
        include 'vues/compta/v_listeFicheValidee.php';
        break;
    case 'mettreEnPaiementFiche':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'MP');
        $succes = "Fiche de frais mise en paiement.";
        include 'vues/v_succes.php';
        break;
    case 'rembourserFrais':
        $pdo->majEtatFicheFrais($idVisiteur, $mois, 'RB');
        $succes = "Fiche de frais remboursée.";
        include 'vues/v_succes.php';
        break;
}

if ($idVisiteur && $mois) {
    
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
    $numAnnee = substr($mois, 0, 4);
    $numMois = substr($mois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $etat = $lesInfosFicheFrais['idEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    $nomVisiteur = $lesInfosFicheFrais['nomVisiteur'] . ' '
        . $lesInfosFicheFrais['prenomVisiteur'];
    include 'vues/compta/v_etatFrais.php';
}