<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) AND $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_etab = $_GET['id'];
            $requete_etab = $pdo->prepare("SELECT * FROM etablissements WHERE etab_id = ?");
            $requete_etab->execute(array($id_etab));
            $requete_etab = $requete_etab->fetch();

            $raisonsocial = $requete_etab['etab_raisonSocial'];
            $secteur = $requete_etab['etab_idSecteur'];
            $type = $requete_etab['etab_idType'];
            $origine = $requete_etab['etab_idOrigine'];
            $departement = $requete_etab['etab_idDepartement'];
            $adresse = $requete_etab['etab_adresse'];
            $ville = $requete_etab['etab_ville'];
            $cp = $requete_etab['etab_cp'];
            $tel = $requete_etab['etab_tel'];
            $mail = $requete_etab['etab_mail'];
            $donnateur = $requete_etab['etab_donnateur'];
        }
        if (isset($_POST['form_etab'])) {
            if (!empty($_POST['newetab']) and isset($_POST['newtype']) and isset($_POST['newdepartement'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newtype = htmlentities($_POST['newtype']);
                $newdepartement = htmlentities($_POST['newdepartement']);
                $newadresse = htmlentities($_POST['newadresse']);
                $newville = htmlentities($_POST['newville']);
                $newcodepostal = htmlentities($_POST['newcp']);
                $newtel = htmlentities($_POST['newtel']);
                $newmail = htmlentities($_POST['newmail']);

                $insertetab = $pdo->prepare("INSERT INTO sites_entreprise(site_idEtab, site_idTypeSite, site_idDepartement, site_adresse, site_ville, site_cp, site_tel, site_mail) VALUES(?,?,?,?,?,?,?,?)");
                $insertetab->execute(array($newetab, $newtype, $newdepartement, $newadresse, $newville, $newcodepostal, $newtel, $newmail));
                $_SESSION['succes'] = "Site ajouté avec succès";
                header("location: fiche_etablissement?id=$newetab&sites=1");
                exit();
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: ajouter_site?id=$id_etab");
                exit();
            }
        }
?>

        <head>
            <title>MFR BDD - Ajouter un site</title>
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
                        <h1>Ajouter un site secondaire</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend" data-select2-open="single-prepend-text">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial") as $unEtab) {
                                            $id_etab2 = $unEtab['etab_id'];
                                            $raisonsocial = $unEtab['etab_raisonSocial'];
                                            if ($id_etab2 == $id_etab) {
                                                echo ("<option value='$id_etab2' selected='selected'>$raisonsocial</option>");
                                            } else {
                                                echo ("<option value='$id_etab2'>$raisonsocial</option>");
                                            }
                                        }
                                        ?>
                                    </select>
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
                                <label for="newtype" class="form-label required">Type de site :</label>
                                <select name="newtype" id="newtype" class="form-control">
                                    <?php
                                    foreach (lireLesUsers($pdo, "SELECT * FROM types_sites ORDER BY typeSite_intitule") as $unType) {
                                        $idtype = $unType['typeSite_id'];
                                        $nomtype = $unType['typeSite_intitule'];
                                        echo ("<option value='$idtype'>$nomtype</option>");
                                    }
                                    ?>
                                </select>
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