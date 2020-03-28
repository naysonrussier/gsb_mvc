<?php
/**
 * Vue Liste des frais au forfait
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
<h2>Paiement fiche frais</h2>
<div class="mb">
    <strong><u>Période :</u></strong> <?php echo $numMois . '-' . $numAnnee ?>
    <br>
    <strong><u>Visiteur :</u></strong> <?php echo $nomVisiteur ?>
    <br>
    <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
    depuis le <?php echo $dateModif ?>
    <br> 
    <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
    <br>
</div>
<div class="panel panel-comptable">
    <div class="panel-heading">Eléments forfaitisés</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle'];
                ?>
                <th> <?php echo htmlspecialchars($libelle) ?></th>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                ?>
                <td class="qteForfait"><?php echo $quantite ?> </td>
                <?php
            }
            ?>
        </tr>
    </table>
</div>
<div class="panel panel-comptable">
    <div class="panel-heading">Descriptif des éléments hors forfait - 
        <?php echo $nbJustificatifs ?> justificatifs reçus</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>                
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $date = $unFraisHorsForfait['date'];
            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
            $montant = $unFraisHorsForfait['montant'];
            ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<?php if ($etat == 'VA') { ?>
<form action="index.php?uc=paiementFrais&action=mettreEnPaiementFiche" method="POST" role="form">
    <input type='hidden' value='<?php echo $mois ?>' name='mois'>
    <input type='hidden' value='<?php echo $idVisiteur ?>' name='visiteur'>
    <input type="submit" class="btn btn-success mb" value="Mettre en Paiement">
</form>
<?php } elseif($etat == 'MP') { ?>

<form action="index.php?uc=paiementFrais&action=rembourserFrais" method="POST" role="form">
    <input type='hidden' value='<?php echo $mois ?>' name='mois'>
    <input type='hidden' value='<?php echo $idVisiteur ?>' name='visiteur'>
    <input type="submit" class="btn btn-success mb" value="Rembourser les frais">
</form>
<?php } ?>