<?php
/**
 * Vue Liste des frais hors forfait
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
?>
<hr>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 text-center">Date</div>
                <div class="col-sm-3">Libellé</div>
                <div class="col-sm-3">Montant</div>
                <div class="col-sm-3">&nbsp;</div>
            </div>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>  
                <form method="post" 
                      action="index.php?uc=validerFrais&action=validerMajFraisHorsForfait&
                        lstMois=<?php echo $mois ?>&lstVisiteur=<?php echo $idVisiteur ?>" 
                      role="form" class="row">

                        <div class="col-sm-3"><input name='dateFrais' class='form-control' value='<?php echo $date ?>'></div>
                        <div class="col-sm-3"><input name='libelle' class='form-control' value='<?php echo $libelle ?>'></div>
                        <div class="col-sm-3"><input name='montant' class='form-control' value='<?php echo $montant ?>'></div>
                        <div class="col-sm-3">
                            <input type='hidden' value='<?php echo $id ?>' name='id'>
                            <input type='submit' class='btn btn-success' value='Corriger'>
                            <input type='reset' class='btn btn-danger' value='Réinitialiser'>
                        </div>

                </form>

                <?php
            }
            ?>
        </div>
    </div>
</div>