<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_ficheCollab = $_GET['id'];
            $requete_collab = $pdo->prepare("SELECT * FROM collab_cfa, participation_collab WHERE participationCollab_idCollab = collab_id AND collab_id = ?");
            $requete_collab->execute(array($id_ficheCollab));
            $requete_collab = $requete_collab->fetch();

            $collab_id = $requete_collab['collab_id'];
            $collab_idEtab = $requete_collab['collab_idEtab'];
            $id_etab = $collab_idEtab;
            $collab_idCfa = $requete_collab['collab_idCFA'];
            $id_cfa = $collab_idCfa;
            $id_raison = $requete_collab['collab_raison'];
        }

        if (isset($_POST['form_eleve_form'])) {
            $newetab = htmlentities($_POST['newetab']);
            $newcfa = htmlentities($_POST['newcfa']);
            $newraison = htmlentities($_POST['newraison']);

            $insertcontact = $pdo->prepare("UPDATE collab_cfa SET collab_idEtab = ?, collab_idCFA = ?, collab_raison = ? WHERE collab_id = ?");
            $insertcontact->execute(array($newetab, $newcfa, $newraison, $id_ficheCollab));
            $_SESSION['succes'] = "Collaboration modifié avec succés";

            $deletecontact = $pdo->prepare("DELETE FROM participation_collab WHERE participationCollab_idCollab = ?");
            $deletecontact->execute(array($id_ficheCollab));
            for ($i = 0; $i < count($_POST['newformation']); $i++) {
                $insertcontact = $pdo->prepare("INSERT INTO participation_collab(participationCollab_idCollab, participationCollab_idFormationCfa) VALUES(?,?)");
                $insertcontact->execute(array($id_ficheCollab, $_POST['newformation'][$i]));
            }
            header("location: fiche_etablissement?id=$newetab&collabes=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Fiche collab CFA</title>
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
                        <h1>Fiche collaboration CFA</h1>
                        <br>

                        <form method="POST" role="form" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="fiche_etablissement?id=<?php echo $id_etab ?>"><i class="fas fa-building"></i></a></span>
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
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="fiche_etablissement?id=<?php echo $id_cfa ?>"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newcfa" id="newcfa">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements WHERE etab_idType = 5 ORDER BY etab_raisonSocial ASC") as $unEtab) {
                                            $id_etab2 = $unEtab['etab_id'];
                                            $raisonsocial = $unEtab['etab_raisonSocial'];
                                            if ($id_etab2 == $id_cfa) {
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
                                    <?php
                                    $i = 0;
                                    foreach (lireLesUsers($pdo, "SELECT * FROM participation_collab WHERE participationCollab_idCollab = $id_ficheCollab") as $uneCollab) {
                                        $participationCollab_idFormation = $uneCollab['participationCollab_idFormationCfa'];
                                    ?>
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
                                                    if ($participationCollab_idFormation == $id_form) {
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
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <label for="newraison" class="form-label">Commentaire</label>
                                    <textarea class="form-control" id="newraison" name="newraison" rows="4" cols="50" placeholder="Ajouter un commentaire..."><?php if (isset($id_raison)) echo $id_raison ?></textarea>
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Modifier</button>
                        </form>

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer cette collaboration</button>

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette collaboration et toutes les données qui lui sont liées ?<br><br>Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <button name="id_collab" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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