<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) AND $id_admin > 1) {
        if (isset($_POST['form_etab'])) {
            if (!empty($_POST['newraisonsocial'])) {
                $newraisonsocial = htmlentities($_POST['newraisonsocial']);
                $newsecteur = htmlentities($_POST['newsecteur']);
                $newtype = htmlentities($_POST['newtype']);
                $neworigine = htmlentities($_POST['neworigine']);
                $newdepartement = htmlentities($_POST['newdepartement']);
                $newadresse = htmlentities($_POST['newadresse']);
                $newville = htmlentities($_POST['newville']);
                $newcodepostal = htmlentities($_POST['newcp']);
                $newtel = htmlentities($_POST['newtel']);
                $newmail = htmlentities($_POST['newmail']);
                $newdonnateur = htmlentities($_POST['newdonnateur']);
                $newinteresse = htmlentities($_POST['newinteresse']);
                $newraison = htmlentities($_POST['newraison']);
                $newurl = htmlentities($_POST['newurl']);

                $insertetab = $pdo->prepare("INSERT INTO etablissements(etab_raisonSocial, etab_idSecteur, etab_idType, etab_idOrigine, etab_idDepartement, etab_adresse, etab_ville, etab_cp, etab_tel, etab_mail, etab_donnateur, etab_interest, etab_raison, etab_url) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $insertetab->execute(array($newraisonsocial, $newsecteur, $newtype, $neworigine, $newdepartement, $newadresse, $newville, $newcodepostal, $newtel, $newmail, $newdonnateur, $newinteresse, $newraison, $newurl));
                $newetab = requeteSQL($pdo, "SELECT * FROM etablissements ORDER BY etab_id DESC", "etab_id");
                $_SESSION['succes'] = "Établissement ajouté avec succès";
                header("location: fiche_etablissement?id=$newetab");
                exit();
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
            }
            header("location: ajouter_etablissement.php");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Ajouter un établissement</title>
        </head>


        <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-1">
                    </div>
                    <div class="col-xl-10">
                        <?php
                        if (isset($_SESSION['erreur'])) {
                            echo "<div class='alert alert-danger' role='alert'>";
                            echo $_SESSION['erreur'];
                            echo "</div>";
                            unset($_SESSION['erreur']);
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['succes'])) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo $_SESSION['succes'];
                            echo "</div>";
                            unset($_SESSION['succes']);
                        }
                        ?>
                        <h1>Ajouter un établissement</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newraisonsocial" class="form-label required">Raison sociale</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="newraisonsocial" name="newraisonsocial" placeholder="Raison sociale" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newadresse" class="form-label">Adresse</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" placeholder="Adresse" name="newadresse" id="newadresse" class="form-control">
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="newdepartement" class="form-label required">Département</label>
                                    <select name="newdepartement" id="newdepartement" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM departement") as $unDepart) {
                                            $iddepart = $unDepart['departement_id'];
                                            $codeDepart = $unDepart['departement_code'];
                                            $nomDepart = $unDepart['departement_nom'];
                                            if ($iddepart == 39) {
                                                echo ("<option value='$iddepart' selected='selected'>$codeDepart - $nomDepart</option>");
                                            } else {
                                                echo ("<option value='$iddepart'>$codeDepart - $nomDepart</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="newcp" class="form-label">Code postal</label>
                                    <input list="cps" type="text" placeholder="Code Postal" name="newcp" id="newcp" class="form-control">
                                    <datalist id="cps">
                                    <?php
                                                    foreach (lireLesUsers($pdo, "SELECT * FROM etablissements GROUP BY etab_cp") as $unCp) {
                                                        $cps = $unCp['etab_cp'];
                                                        echo ("<option value='$cps'>");
                                                    }
                                                    ?>
                                    </datalist>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="newville" class="form-label">Ville</label>
                                    <input list="villes" type="text" placeholder="Ville" name="newville" id="newville" class="form-control">
                                    <datalist id="villes">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements GROUP BY etab_ville") as $uneVille) {
                                            $ville = $uneVille['etab_ville'];
                                            echo ("<option value='$ville'>");
                                        }
                                        ?>
                                    </datalist>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newtel" class="form-label">Tél</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél" name="newtel" id="newtel" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newmail" class="form-label">Mail</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" placeholder="Adresse mail" name="newmail" id="newmail" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="newurl" class="form-label">Site internet</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" placeholder="https://site-internet.com" name="newurl" id="newurl" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newtype" class="form-label required">Type d'établissement</label>
                                    <select name="newtype" id="newtype" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM types_entreprise") as $unType) {
                                            $idtype = $unType['typeEnt_id'];
                                            $nomtype = $unType['typeEnt_intitule'];
                                            $nbrsalaries = $unType['typeEnt_nbrSalaries'];
                                            if ($idtype == 5) {
                                                echo ("<option value='$idtype'>$nomtype</option>");
                                            } else {
                                                echo ("<option value='$idtype'>$nomtype ($nbrsalaries salariés)</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newsecteur" class="form-label required">Secteur d'activité</label>
                                    <select name="newsecteur" id="newsecteur" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM secteurs_entreprise ORDER BY secteur_nom") as $unSecteur) {
                                            $idsecteur = $unSecteur['secteur_id'];
                                            $nomsecteur = $unSecteur['secteur_nom'];
                                            echo ("<option value='$idsecteur'>$nomsecteur</option>");
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="neworigine" class="form-label required">Origine de l'établissement</label>
                                    <select name="neworigine" id="neworigine" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM origines_entreprise ORDER BY origineEnt_intitule") as $uneOrigine) {
                                            $idorigine = $uneOrigine['origineEnt_id'];
                                            $nomorigine = $uneOrigine['origineEnt_intitule'];
                                            echo ("<option value='$idorigine'>$nomorigine</option>");
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newdonnateur" class="form-label required">Donateur</label>
                                    <select name="newdonnateur" id="newdonnateur" class="form-control">
                                        <option value='1'>Oui</option>
                                        <option value='0' selected="selected">Non</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newinteresse" class="form-label required">Intéressé par les offres d'alternances</label>
                                <select name="newinteresse" id="newinteresse" class="form-control">
                                    <option value='1' selected="selected">Oui</option>
                                    <option value='0'>Non</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div id="hide-me">
                                    <label for="newraison" class="form-label">Raison</label>
                                    <textarea class="form-control" id="newraison" name="newraison" rows="4" cols="50" placeholder="Pourquoi cet établissement n'accepte pas les alternant(e)s..."></textarea>
                                </div>
                            </div>


                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_etab" name="form_etab">Ajouter</button>
                        </form>
                    </div>
                    <div class="col-xl-1">
                    </div>
                </div>
            </div>
        </body>
        <?php
        require 'footer.php';
        ?>

</html>
<?php
    } else {
        $_SESSION['erreur'] = "Vous n'avez pas les droits !";
        header("location: index.php");
        exit();
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
?>