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
        if (isset($_GET['id_contact'])) {
            $entretien_idContact = $_GET['id_contact'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $entretien_idContact", "contact_idEtab");
        } else {
           $entretien_idContact = "";
        }
        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newdate'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newsite = htmlentities($_POST['newsite']);
                $newtype = htmlentities($_POST['newtype']);
                $newcommentaire = htmlentities($_POST['newcommentaire']);
                $newdate = htmlentities($_POST['newdate']);

                $insertcontact = $pdo->prepare("INSERT INTO evenements(event_idEtab, event_idSite, event_idTypeEvent, event_commentaire, event_date) VALUES(?,?,?,?,?)");
                $insertcontact->execute(array($newetab, $newsite, $newtype, $newcommentaire, $newdate));
                $_SESSION['succes'] = "Événement ajouté avec succés";

                $last_id_event = requeteSQL($pdo, "SELECT * FROM evenements ORDER BY event_id DESC", "event_id");
                $que_des_zero = true;
                for ($i = 0; $i < count($_POST['newcontact']); $i++) {
                    if ($_POST['newcontact'][$i] != 0) {
                        $que_des_zero = false;
                        $insertcontact = $pdo->prepare("INSERT INTO participation_event(participation_idEvent, participation_idContact) VALUES(?,?)");
                        $insertcontact->execute(array($last_id_event, $_POST['newcontact'][$i]));
                    }
                }
                if ($que_des_zero) {
                    $insertcontact = $pdo->prepare("INSERT INTO participation_event(participation_idEvent, participation_idContact) VALUES(?,?)");
                    $insertcontact->execute(array($last_id_event, '0'));
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: ajouter_evenement?id=$id_etab");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&events=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Ajouter un évènement</title>
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
                        <h1>Ajouter un évènement</h1>
                        <br>

                        <form method="POST" role="form" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='ajouter_evenement?id='+this.value">
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
                                    <option value="0">Site Principal</option>
                                    <?php
                                    foreach (lireLesUsers($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_idEtab = '$id_etab' ORDER BY typeSite_intitule") as $unSite) {
                                        $id_etab_site = $unSite['site_id'];
                                        $id_etab_site_nom = "Site " . $unSite['typeSite_intitule'] . " de " . $unSite['site_ville'] . " (" . $unSite['site_adresse'] . ")";
                                        if ($id_etab_site == $id_site) {
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
                                        echo ("<option value='$typeEvent_id'>$typeEvent_intitule</option>");
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="newcommentaire" class="form-label">Description de l'évènement</label>
                                <textarea class="form-control" id="newcommentaire" name="newcommentaire" rows="4" cols="50" placeholder="Description de l'évènement..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="newdate" class="form-label required">Date</label>
                                <input class="form-control" type="date" name="newdate" id="newdate" value="<?php echo date('Y-m-d') ?>">
                            </div>

                            <div class="form-group">
                                <label for="newcontact" class="form-label required">Contact(s)</label>
                                <div class="dynamic-wrap">
                                    <div class="entry input-group">
                                        <select name="newcontact[]" id="newcontact" class="form-control">
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
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add btnevent" type="button">
                                                <span>+</span>
                                            </button>
                                        </span>
                                    </div>
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