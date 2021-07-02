<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_ficheEntretien = $_GET['id'];
            $requete_entretien = $pdo->prepare("SELECT * FROM entretiens WHERE entretien_id = ?");
            $requete_entretien->execute(array($id_ficheEntretien));
            $requete_entretien = $requete_entretien->fetch();

            $entretien_idContact = $requete_entretien['entretien_idContact'];
            $entretien_idMembre = $requete_entretien['entretien_idMembre'];
            $entretien_contenu = $requete_entretien['entretien_contenu'];
            $entretien_dateAppel = date('Y-m-d', strtotime($requete_entretien['entretien_dateAppel']));
            $entretien_timeAppel = date('H:i', strtotime($requete_entretien['entretien_dateAppel']));
            $entretien_dateRappel = date('Y-m-d', strtotime($requete_entretien['entretien_dateRappel']));
            $entretien_timeRappel = date('H:i', strtotime($requete_entretien['entretien_dateRappel']));
            $entretien_timeRappel2 = date('H:i', strtotime($requete_entretien['entretien_dateRappel2']));
            $entretien_reponse = $requete_entretien['entretien_reponse'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $entretien_idContact", "contact_idEtab");
            $newnext = $requete_entretien['entretien_idNextEntretien'];
        }
        if (isset($_GET['id_etab'])) {
            $id_etab = $_GET['id_etab'];
        }

        if (isset($_POST['form_entretien'])) {
            if ($_POST['newcontact'] != 0) {
                $newetab = htmlentities($_POST['newetab']);
                $newcontact = htmlentities($_POST['newcontact']);
                $newmembre = htmlentities($_POST['newmembre']);
                $newcontenu = htmlentities($_POST['newcontenu']);
                $newreponse = htmlentities($_POST['newreponse']);

                $dateAppel = htmlentities($_POST['newdateAppel']);
                $timeAppel = htmlentities($_POST['newdateAppelTime']);
                $newdateAppel = date('Y-m-d H:i:s', strtotime("$dateAppel $timeAppel"));

                $newsite = htmlentities($_POST['newsite']);
                $newfonction = htmlentities($_POST['newfonction']);
                $newnom = htmlentities($_POST['newnom']);
                $newprenom = htmlentities($_POST['newprenom']);
                $newtel = htmlentities($_POST['newtel']);
                $newtelpro = htmlentities($_POST['newtelpro']);
                $newmail = htmlentities($_POST['newmail']);
                $newmailpro = htmlentities($_POST['newmailpro']);

                if (!empty($_POST['newdateRappel'])) {
                    $newdateRappel = $_POST['newdateRappel'];
                    if (!empty($_POST['newdateRappelTime'])) {
                        $newdateRappelTime_1 = $_POST['newdateRappelTime'];
                        $newdateRappel_1 = date('Y-m-d H:i:s', strtotime("$newdateRappel $newdateRappelTime_1"));
                        $newnext = -1;
                    }
                    if (!empty($_POST['newdate2RappelTime'])) {
                        $newdateRappelTime_2 = $_POST['newdate2RappelTime'];
                        $newdateRappel_2 = date('Y-m-d H:i:s', strtotime("$newdateRappel $newdateRappelTime_2"));
                        $newnext = -1;
                    } else {
                        $newdateRappel_2 = NULL;
                    }
                } else {
                    $newdateRappel_1 = NULL;
                    $newnext = 0;
                }


                $insertentretien = $pdo->prepare("UPDATE entretiens SET entretien_idContact = ?, entretien_idMembre = ?, entretien_contenu = ?, entretien_dateAppel = ?, entretien_dateRappel = ?, entretien_dateRappel2 = ?, entretien_reponse = ?, entretien_idNextEntretien = ? WHERE entretien_id = $id_ficheEntretien");
                $insertentretien->execute(array($newcontact, $newmembre, $newcontenu, $newdateAppel, $newdateRappel_1, $newdateRappel_2, $newreponse, $newnext));
                $_SESSION['succes'] = "Entretien modifié avec succès";
            } else {
                $_SESSION['erreur'] = "Choisir un contact !";
                header("location: ajouter_entretien?id=$id_ficheEntretien&id_etab=" . $_POST['newetab']);
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&entretiens=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Fiche entretien</title>
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
                        <h1>Fiche entretien</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a target="_blank" class="hover-logo" href="fiche_etablissement?id=<?php echo $id_etab ?>"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='fiche_entretien?id=<?php echo $id_ficheEntretien ?>&id_etab='+this.value">
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
                                <label for="newcontact" class="form-label required">Contact</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickContact()"><i class="fas fa-user-tie"></i></a></span>
                                    </div>
                                    <select name="newcontact" id="newcontact" class="form-control">
                                        <option value="0">Choisir un contact</option>
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_idEtab = '$id_etab'") as $unContact) {
                                            $contact_id = $unContact['contact_id'];
                                            $contact_nom = $unContact['contact_nom'] . " " . $unContact['contact_prenom'];
                                            if ($contact_id == $entretien_idContact) {
                                                echo ("<option value='$contact_id' selected='selected'>$contact_nom</option>");
                                            } else {
                                                echo ("<option value='$contact_id'>$contact_nom</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newmembre" class="form-label required">Interlocuteur MFR :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickMembre()"><i class="fas fa-user-tie"></i></a></span>
                                    </div>
                                    <select name="newmembre" id="newmembre" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM membres") as $unMembre) {
                                            $membre_id = $unMembre['memb_id'];
                                            $membre_nom = $unMembre['memb_nom'] . " " . $unMembre['memb_prenom'];
                                            if ($membre_id == $entretien_idMembre) {
                                                echo ("<option value='$membre_id' selected='selected'>$membre_nom</option>");
                                            } else {
                                                echo ("<option value='$membre_id'>$membre_nom </option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newreponse" class="form-label required">Réponse</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-reply"></i></span>
                                    </div>
                                    <select name="newreponse" id="newreponse" class="form-control">
                                        <option value='1'>Oui</option>
                                        <option value='0' <?php if (isset($entretien_reponse) and $entretien_reponse == 0) echo "selected='selected'" ?>>Non</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="hide-me3">
                                    <label for="newcontenu" class="form-label">Contenu</label>
                                    <textarea class="form-control" id="newcontenu" name="newcontenu" rows="4" cols="50" placeholder="Contenu de l'entretien..."><?php if (isset($entretien_contenu)) echo $entretien_contenu ?></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="newdateAppel" class="form-label required">Date entretien</label>
                                    <input type="date" name="newdateAppel" id="newdateAppel" class="form-control" value="<?php if (isset($entretien_dateAppel)) echo $entretien_dateAppel ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdateAppelTime" class="form-label required">Heure</label>
                                    <input type="time" name="newdateAppelTime" id="newdateAppelTime" class="form-control" value="<?php if (isset($entretien_timeAppel)) echo $entretien_timeAppel ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="newdateRappel" class="form-label">Date de rappel</label>
                                    <input type="date" name="newdateRappel" id="newdateRappel" class="form-control" onchange="plageAuto()" value="<?php if (isset($entretien_dateRappel)) echo $entretien_dateRappel ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdateRappelTime" class="form-label" class="form-label required">Plage de rappel</label>
                                    <input type="time" name="newdateRappelTime" id="newdateRappelTime" class="form-control" onchange="timeAuto('newdateRappelTime', 'newdate2RappelTime')" value="<?php if (isset($entretien_timeRappel)) echo $entretien_timeRappel ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdate2RappelTime" class="form-label" class="form-label required" style="color:white">Heure</label>
                                    <input type="time" name="newdate2RappelTime" id="newdate2RappelTime" class="form-control" value="<?php if (isset($entretien_timeRappel2)) echo $entretien_timeRappel2 ?>">
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_entretien" name="form_entretien">Modifier</button>
                        </form>

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer cet entretien</button>

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
                                        Êtes-vous sûr de vouloir supprimer cet entretien et toutes les données qui lui sont liées ?<br><br>
                                        Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <button name="id_entretien" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
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
        <script src="js/index.js"></script>
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