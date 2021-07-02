<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_ficheEvent = $_GET['id'];
            $requete_event = $pdo->prepare("SELECT * FROM evenements WHERE event_id = ?");
            $requete_event->execute(array($id_ficheEvent));
            $requete_event = $requete_event->fetch();

            $requete_contact = $pdo->prepare("SELECT * FROM participation_event WHERE participation_idEvent = ?");
            $requete_contact->execute(array($id_ficheEvent));
            $requete_contact = $requete_contact->fetch();

            $event_id = $requete_event['event_id'];
            $event_idEtab = $requete_event['event_idEtab'];
            $event_idSite = $requete_event['event_idSite'];
            $event_idTypeEvent = $requete_event['event_idTypeEvent'];
            $event_commentaire = $requete_event['event_commentaire'];
            $event_date = $requete_event['event_date'];
        }
        if (isset($_GET['id_etab'])) {
            $id_etab = $_GET['id_etab'];
        } else {
            $id_etab = requeteSQL($pdo, "SELECT * FROM evenements WHERE event_id = $event_id", "event_idEtab");
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
        }
        if (isset($_GET['id_contact'])) {
            $id_contact = $_GET['id_contact'];
        }

        if (isset($_POST['form_eleve_form'])) {
            if ($_POST['newcontact'] != 0) {
                $newetab = htmlentities($_POST['newetab']);
                $newsite = htmlentities($_POST['newsite']);
                $newtype = htmlentities(htmlentities($_POST['newtype']));
                $newcommentaire = htmlentities(htmlentities($_POST['newcommentaire']));
                $newdate = htmlentities(htmlentities($_POST['newdate']));

                $insertcontact = $pdo->prepare("UPDATE evenements SET event_idEtab = ?, event_idSite = ?, event_idTypeEvent = ?, event_commentaire = ?, event_date = ? WHERE event_id = ?");
                $insertcontact->execute(array($newetab, $newsite, $newtype, $newcommentaire, $newdate, $id_ficheEvent));
                $_SESSION['succes'] = "Évènement modifié avec succés";

                $deletecontact = $pdo->prepare("DELETE FROM participation_event WHERE participation_idEvent = ?");
                $deletecontact->execute(array($id_ficheEvent));
                for ($i = 0; $i < count($_POST['newcontact']); $i++) {
                    if (!empty($_POST['newcontact'][$i])) {

                        $insertcontact = $pdo->prepare("INSERT INTO participation_event(participation_idEvent, participation_idContact) VALUES(?,?)");
                        $insertcontact->execute(array($id_ficheEvent, $_POST['newcontact'][$i]));
                    }
                }
            } else {
                $_SESSION['erreur'] = "Ajouter un contact !";
                header("location: ajouter_evenement.php");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&events=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Fiche évènement</title>
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
                        <h1>Fiche évènement</h1>
                        <br>

                        <form method="POST" role="form" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="fiche_etablissement?id=<?php echo $id_etab ?>"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='fiche_evenement?id=<?php echo $id_ficheEvent ?>&id_etab='+this.value">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial ASC") as $unEtab) {
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
                                <label for="newsite" class="form-label required">Site d'accueil</label>
                                <select name="newsite" id="newsite" class="form-control">
                                    <option value="-1">MFR</option>
                                    <option value="0" <?php if ($event_idSite == 0) echo "selected='selected'" ?>>Site Principal</option>
                                    <?php
                                    foreach (lireLesUsers($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_idEtab = '$id_etab' ORDER BY typeSite_intitule") as $unSite) {
                                        $id_etab_site = $unSite['site_id'];
                                        $id_etab_site_nom = "Site " . $unSite['typeSite_intitule'] . " de " . $unSite['site_ville'] . " (" . $unSite['site_adresse'] . ")";
                                        if ($id_etab_site == $event_idSite) {
                                            echo ("<option value='$id_etab_site' selected='selected'>$id_etab_site_nom</option>");
                                        } else {
                                            echo ("<option value='$id_etab_site'>$id_etab_site_nom</option>");
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="newtype" class="form-label required">Type d'évènement</label>
                                <select name="newtype" id="newtype" class="form-control">
                                    <?php
                                    foreach (lireLesUsers($pdo, "SELECT * FROM types_evenements") as $unType) {
                                        $typeEvent_id = $unType['typeEvent_id'];
                                        $typeEvent_intitule = $unType['typeEvent_intitule'];
                                        if ($typeEvent_id == $event_idTypeEvent) {
                                            echo ("<option value='$typeEvent_id' selected='selected'>$typeEvent_intitule</option>");
                                        } else {
                                            echo ("<option value='$typeEvent_id'>$typeEvent_intitule</option>");
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="newcommentaire" class="form-label">Description de l'évènement</label>
                                <textarea class="form-control" id="newcommentaire" name="newcommentaire" rows="4" cols="50" placeholder="Description de l'évènement..."><?php if (isset($event_commentaire)) echo $event_commentaire ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="newdate" class="form-label required">Date</label>
                                <input class="form-control" type="date" name="newdate" id="newdate" value="<?php if (isset($event_date)) echo $event_date ?>">
                            </div>

                            <div class="form-group">
                                <label for="newcontact" class="form-label required">Contact(s)</label>
                                <div class="dynamic-wrap">
                                    <?php
                                    $i = 0;
                                    foreach (lireLesUsers($pdo, "SELECT * FROM participation_event WHERE participation_idEvent = $id_ficheEvent") as $unContactField) {
                                        $id_contact_event = $unContactField['participation_idContact'];
                                    ?>
                                        <div class="entry input-group">
                                            <select name="newcontact[]" id="newcontact" class="form-control">
                                                <option value="0">Choisir un contact</option>
                                                <?php
                                                foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_idEtab = '$id_etab'") as $unContact) {
                                                    $contact_id = $unContact['contact_id'];
                                                    $contact_nom = $unContact['contact_nom'] . " " . $unContact['contact_prenom'];
                                                    if ($contact_id == $id_contact_event) {
                                                        echo ("<option value='$contact_id' selected='selected'>$contact_nom</option>");
                                                    } else {
                                                        echo ("<option value='$contact_id'>$contact_nom</option>");
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


                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Modifier</button>
                        </form>

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer cet évènement</button>

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
                                        Êtes-vous sûr de vouloir supprimer cet évènement et toutes les données qui lui sont liées ?<br><br>Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <button name="id_event" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
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