<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
$estComptable = estComptable();
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

require 'vues/v_entete.php';
if ($uc && !$estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}
// Routage standard
switch ($uc) {
    case 'connexion':
        include 'controleurs/c_connexion.php';
        break;
    case 'accueil':
        include 'controleurs/c_accueil.php';
        break;
    case 'deconnexion':
        include 'controleurs/c_deconnexion.php';
        break;
    // Routage suivant le type d'utilisateur (comptable / visiteur)
    default:
        if ($_SESSION['type'] == 'visiteur') {
            switch ($uc) {
                case 'gererFrais':
                    include 'controleurs/c_gererFrais.php';
                    break;
                case 'etatFrais':
                    include 'controleurs/c_etatFrais.php';
                    break;
            }
        } elseif ($_SESSION['type'] == 'comptable') {
            switch ($uc) {
                case 'validerFrais':
                    include 'controleurs/compta/c_validerFrais.php';
                    break;
                case 'paiementFrais':
                    include 'controleurs/compta/c_paiementFrais.php';
                    break;
            }
        }
}
require 'vues/v_pied.php';
