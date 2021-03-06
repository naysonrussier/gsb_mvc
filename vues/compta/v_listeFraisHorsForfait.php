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
<div class="row comptable_hf">
    <div class="panel panel-comptable">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
            <div class="table-header">
                <div class="col-sm-3">Date</div>
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
                    action="index.php?uc=validerFrais&action=validerMajFraisHorsForfait"
                    role="form">

                        <div class="col-sm-3">
                            <input name='dateFrais' class='form-control' value='<?php echo $date ?>'>
                        </div>
                        <div class="col-sm-3">
                            <input name='libelle' class='form-control' value='<?php echo $libelle ?>'>
                        </div>
                        <div class="col-sm-3">
                            <input name='montant' class='form-control' value='<?php echo $montant ?>'>
                        </div>
                        <div class="col-sm-3">
                            <input type='hidden' value='<?php echo $id ?>' name='id'>
                            <input type='hidden' value='<?php echo $mois ?>' name='mois'>
                            <input type='hidden' value='<?php echo $idVisiteur ?>' name='visiteur'>
                            <button type='submit' class='btn btn-success'>
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                            <a class='btn btn-danger' href='<?php
                                echo 'index.php?uc=validerFrais'
                                . '&action=refusFraisHorsFofrait&idFrais=' . $id
                                . '&visiteur=' . $idVisiteur . '&mois=' . $mois;
                            ?>' onclick="return confirm('Voulez-vous vraiment refuser ce frais?');">
                                <span class="glyphicon glyphicon-remove"></span> Refus
                            </a>
                            <a class='btn btn-warning' href='<?php
                                echo 'index.php?uc=validerFrais'
                                . '&action=reporterFraisHorsFofrait&idFrais=' . $id
                                . '&visiteur=' . $idVisiteur . '&mois=' . $mois;
                            ?>' onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">
                                <span class="glyphicon glyphicon-share-alt"></span> Report
                            </a>
                        </div>

                </form>

                <?php
            }
            ?>
    </div>
</div>