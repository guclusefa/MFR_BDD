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
        } else {
            $id_etab = requeteSQL($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial", "etab_id");
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $id_site", "site_idEtab");
        }

        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newformation'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newcfa = htmlentities($_POST['newcfa']);
                $newraison = htmlentities($_POST['newraison']);

                $insertcontact = $pdo->prepare("INSERT INTO collab_cfa(collab_idEtab, collab_idCfa, collab_raison) VALUES(?,?,?)");
                $insertcontact->execute(array($newetab, $newcfa, $newraison));
                $_SESSION['succes'] = "Collaboration ajouté avec succés";

                $last_id_collab = requeteSQL($pdo, "SELECT * FROM collab_cfa ORDER BY collab_id DESC", "collab_id");
                $que_des_zero = true;
                for ($i = 0; $i < count($_POST['newformation']); $i++) {
                    if ($_POST['newformation'][$i] != 0) {
                        $que_des_zero = false;
                        $insertcontact = $pdo->prepare("INSERT INTO participation_collab(participationCollab_idCollab, participationCollab_idFormationCfa) VALUES(?,?)");
                        $insertcontact->execute(array($last_id_collab, $_POST['newformation'][$i]));
                    }
                }
                if ($que_des_zero) {
                    $insertcontact = $pdo->prepare("INSERT INTO participation_collab(participationCollab_idCollab, participationCollab_idFormationCfa) VALUES(?,?)");
                    $insertcontact->execute(array($last_id_collab, '0'));
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: ajouter_collab?id=$newetab");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&collabes=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Ajouter collaboration CFA</title>
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
                        <h1>Ajouter collaboration CFA</h1>
                        <br>

                        <form method="POST" role="form" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements WHERE etab_idType != 5 ORDER BY etab_raisonSocial ASC") as $unEtab) {
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
                                <label for="newcfa" class="form-label required">CFA</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab2()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newcfa" id="newcfa">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements WHERE etab_idType = 5 ORDER BY etab_raisonSocial ASC") as $unEtab) {
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
                                <label for="newformation" class="form-label required">Formation(s)</label>
                                <div class="dynamic-wrap">
                                    <div class="entry input-group">
                                                                            <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-graduation-cap"></i></span>
                                        </div>
                                        <select name="newformation[]" id="newformation" class="form-control">
                                            <option value="0">Choisir une formation</option>
                                            <?php
                                            foreach (lireLesUsers($pdo, "SELECT * FROM formation_cfa WHERE formationCfa_id != 0") as $uneForm) {
                                                $id_form = $uneForm['formationCfa_id'];
                                                $nom_form = $uneForm['formationCfa_intitule'];
                                                if ($id_formation == $id_form) {
                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                } else {
                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add btnevent" type="button">
                                                <span>+</span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <label for="newraison" class="form-label">Commentaire</label>
                                    <textarea class="form-control" id="newraison" name="newraison" rows="4" cols="50" placeholder="Ajouter un commentaire..."><?php if (isset($id_raison)) echo $id_raison ?></textarea>
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Ajouter</button>
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