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
?>
<div class="row">
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>Visiteur</th>
                <th>Date</th>
                <th>Nb justificatifs</th>
                <th>Montant</th>
                <th>Dernière modification</th>
                <th>Etat</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lesFiches as $uneFiche) {
                $dateModif = $uneFiche['dateModif'];
                $nbJustificatifs = $uneFiche['nbJustificatifs'];
                $montant = $uneFiche['montantValide'];
                $etat = $uneFiche['libEtat'];
                $nomVisiteur = $uneFiche['nomVisiteur'] . ' ' . $uneFiche['prenomVisiteur'];
                $leMois = substr($uneFiche['mois'], 4, 2);
                $annee = substr($uneFiche['mois'], 0, 4);
                $date = $leMois . "/" . $annee;
                $visiteur = $uneFiche['idVisiteur'];
                ?>

                <tr>
                    <td><?php echo $nomVisiteur ?></td>
                    <td><?php echo $date ?></td>
                    <td><?php echo $nbJustificatifs ?></td>
                    <td><?php echo $montant ?></td>
                    <td><?php echo $dateModif ?></td>
                    <td><?php echo $etat ?></td>
                    <td>
                        <form method="post" 
                              action="index.php?uc=paiementFrais&action=selectionnerFiche" 
                              role="form">

                            <input type='hidden' value='<?php echo $annee . $leMois ?>' name='mois'>
                            <input type='hidden' value='<?php echo $visiteur ?>' name='visiteur'>
                            <input type="submit" class="btn btn-primary" value="Détail">
                        </form>
                    </td>
                </tr>


                <?php
            }
            ?>
        </tbody>
    </table>
</div>