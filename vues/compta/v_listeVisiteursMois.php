<?php
/**
 * Vue Liste des mois
 *
 * PHP Version 7.2
 *
 * @category  PPE
 * @package   GSB
 * @author    Nayson Russier <nayson.russier@harmony-group.fr>
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<div class="row">
    <form action="index.php?uc=validerFrais&action=validerVisiteurMois" 
          method="get" role="form" class="form-inline">
        <input type='hidden' value='validerFrais' name='uc'>
        <div class="form-group mb-2">
            <label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
            <select id="lstVisiteurs" name="lstVisiteur" class="form-control" onchange="this.form.submit()">
                <?php
                foreach ($lesVisiteurs as $unVisiteur) {
                    $visiteur = $unVisiteur['id'];
                    $nom = $unVisiteur['prenom'] . ' ' . $unVisiteur['nom'];
                    if ($visiteur == $idVisiteur) {
                        ?>
                        <option selected value="<?php echo $visiteur ?>">
                            <?php echo $nom ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $visiteur ?>">
                            <?php echo $nom ?> </option>
                        <?php
                    }
                }
                ?>    

            </select>
        </div>
        <div class="form-group mb-2">
            <label for="lstMois" accesskey="n">Mois : </label>
            <select id="lstMois" name="lstMois" class="form-control" onchange="this.form.submit()">
                <?php
                foreach ($lesMois as $unMois) {
                    $leMois = $unMois['mois'];
                    $numAnnee = $unMois['numAnnee'];
                    $numMois = $unMois['numMois'];
                    if ($mois == $leMois) {
                        ?>
                        <option selected value="<?php echo $leMois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $leMois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    }
                }
                ?>    

            </select>
        </div>
    </form>
</div>