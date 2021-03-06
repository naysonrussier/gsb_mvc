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
    <div class="col-md-4">
        <form action="index.php?uc=validerFrais&action=validerFrais" 
              method="post" role="form">
            <fieldset> 
                <div class="form-group">
                    <label for="nbJustificatifs">Nb justificatifs</label>
                    <input type="text" id="nbJustificatifs" 
                           name="nbJustificatifs"
                           value="<?php echo $nbJustificatifs ?>" 
                           class="form-control">
                </div>
                <input type='hidden' value='<?php echo $mois ?>' name='mois'>
                <input type='hidden' value='<?php echo $idVisiteur ?>' name='visiteur'>
                <button class="btn btn-success" type="submit">Valider</button>
                <button class="btn btn-danger" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>