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
        } else if (isset($_GET['id_etab'])) {
            $id_etab = $_GET['id_etab'];
        } else {
            $id_etab = requeteSQL($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial", "etab_id");
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $id_site", "site_idEtab");
        }

        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
        } else {
            $id_site = 0;
        }
        if (isset($_GET['id_contact'])) {
            $id_contact = $_GET['id_contact'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $id_contact", "contact_idEtab");
        } else {
            $id_contact = 0;
        }

        if (isset($_GET['id_entretien'])) {
            $id_entretien = $_GET['id_entretien'];
            foreach (lireLesUsers($pdo, "SELECT * FROM entretiens WHERE entretien_id = $id_entretien") as $info_ent) {
                $id_contact = $info_ent['entretien_idContact'];
                $id_etab = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $id_contact", "contact_idEtab");
                $id_membre = $info_ent['entretien_idMembre'];
                $entretien_contenu = $info_ent['entretien_contenu'];
                $entretien_dateAppel = $info_ent['entretien_dateAppel'];
                $entretien_dateRappel = $info_ent['entretien_dateRappel'];
                $entretien_dateRappel2 = $info_ent['entretien_dateRappel2'];
                $entretien_reponse = $info_ent['entretien_reponse'];
                $entretien_idNextEntretien = $info_ent['entretien_idNextEntretien'];
            }
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
                $newnom = strtoupper(htmlentities($_POST['newnom']));
                $newprenom = ucwords(htmlentities($_POST['newprenom']));
                $newtel = htmlentities($_POST['newtel']);
                $newtelpro = htmlentities($_POST['newtelpro']);
                $newmail = htmlentities($_POST['newmail']);
                $newmailpro = htmlentities($_POST['newmailpro']);

                if (isset($_GET['id_entretien'])) {
                    $newnext = requeteSQL($pdo, "SELECT * FROM entretiens ORDER BY entretien_id DESC", "entretien_id");
                    $newnext = $newnext + 1;
                    $updateentretien = $pdo->prepare("UPDATE entretiens SET entretien_idNextEntretien = ? WHERE entretien_id = ?");
                    $updateentretien->execute(array($newnext, $id_entretien));
                }
                $newnext = "-1";

                if ($newcontact == "-1") {
                    $newetab = htmlentities($_POST['newetab']);
                    $newsite = htmlentities($_POST['newsite_contact']);
                    $newfonction = strtoupper(htmlentities($_POST['newfonction_contact']));
                    $newnom = strtoupper(htmlentities($_POST['newnom_contact']));
                    $newprenom = ucwords(htmlentities($_POST['newprenom_contact']));
                    $newtel = htmlentities($_POST['newtel_contact']);
                    $newmail = htmlentities($_POST['newmail_contact']);
                    $newtelpro = htmlentities($_POST['newtelpro_contact']);
                    $newmailpro = htmlentities($_POST['newmailpro_contact']);
                    $newportable = htmlentities($_POST['newportable_contact']);
                    $newportablepro = htmlentities($_POST['newportablepro_contact']);
                    $newsexe = htmlentities($_POST['newsexe']);

                    $insertcontact = $pdo->prepare("INSERT INTO contacts(contact_idEtab, contact_idSite, contact_idFonction, contact_nom, contact_prenom, contact_tel, contact_mail, contact_telpro, contact_mailpro, contact_portable, contact_portablepro, contact_sexe) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insertcontact->execute(array($newetab, $newsite, $newfonction, $newnom, $newprenom, $newtel, $newmail, $newtelpro, $newmailpro, $newportable, $newportablepro, $newsexe));
                    $newcontact = requeteSQL($pdo, "SELECT * FROM contacts ORDER BY contact_id DESC", "contact_id");
                }

                if (!empty($_POST['newdateRappel'])) {
                    $newdateRappel = $_POST['newdateRappel'];
                    if (!empty($_POST['newdateRappelTime'])) {
                        $newdateRappelTime_1 = $_POST['newdateRappelTime'];
                        if (empty($newdateRappelTime_1)) {
                            $newdateRappelTime_1 = "09:00:00";
                        }
                        $newdateRappel_1 = date('Y-m-d H:i:s', strtotime("$newdateRappel $newdateRappelTime_1"));
                    }
                    if (!empty($_POST['newdate2RappelTime'])) {
                        $newdateRappelTime_2 = $_POST['newdate2RappelTime'];
                        if (empty($newdateRappelTime_2)) {
                            $newdateRappelTime_1 = "18:00:00";
                        }
                        $newdateRappel_2 = date('Y-m-d H:i:s', strtotime("$newdateRappel $newdateRappelTime_2"));
                    } else {
                        $newdateRappel_2 = NULL;
                    }
                } else {
                    $newdateRappel_1 = NULL;
                    $newnext = 0;
                }


                $insertentretien = $pdo->prepare("INSERT INTO entretiens(entretien_idContact, entretien_idMembre, entretien_contenu, entretien_dateAppel, entretien_dateRappel, entretien_dateRappel2, entretien_reponse, entretien_idNextEntretien) VALUES(?,?,?,?,?,?,?,?)");
                $insertentretien->execute(array($newcontact, $newmembre, $newcontenu, $newdateAppel, $newdateRappel_1, $newdateRappel_2, $newreponse, $newnext));
                $_SESSION['succes'] = "Entretien ajouté avec succès";
            } else {
                $_SESSION['erreur'] = "Choisir un contact !";
                header("location: ajouter_entretien?id_etab=" . $_POST['newetab']);
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&entretiens=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Ajouter un entretien</title>
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
                        <h1>Ajouter un entretien</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="select2 form-control" name="newetab" id="newetab" onchange="window.location.href='ajouter_entretien?id='+this.value">
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
                                <label for="newcontact" class="form-label required">Contact</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickContact()"><i class="fas fa-user-tie"></i></a></span>
                                    </div>
                                    <select name="newcontact" id="newcontact" class="form-control">
                                        <option value="0">Choisir un contact</option>
                                        <option value="-1">Nouveau contact</option>
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_idEtab = '$id_etab'") as $unContact) {
                                            $contact_id = $unContact['contact_id'];
                                            $contact_nom = $unContact['contact_nom'] . " " . $unContact['contact_prenom'];
                                            if ($contact_id == $id_contact) {
                                                echo ("<option value='$contact_id' selected='selected'>$contact_nom</option>");
                                            } else {
                                                echo ("<option value='$contact_id'>$contact_nom</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div id="hide-me4">
                                <div class="form-group">
                                    <label for="newsite_contact" class="form-label required">Site contact</label>
                                    <select name="newsite_contact" id="newsite_contact" class="form-control">
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
                                    <label for="newfonction_contact" class="form-label required">Fonction contact</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user-tie"></i></span>
                                        </div>
                                        <select name="newfonction_contact" id="newfonction_contact" class="form-control">
                                            <?php
                                            foreach (lireLesUsers($pdo, "SELECT * FROM fonctions_contacts") as $uneFonction) {
                                                $fonction_id = $uneFonction['fonction_id'];
                                                $fonction_intitule = $uneFonction['fonction_intitule'];
                                                echo ("<option value='$fonction_id'>$fonction_intitule</option>");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="newsexe" class="form-label required">Civilité contact</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-mars"></i></span>
                                        </div>
                                        <select name="newsexe" id="newsexe" class="form-control">
                                            <option>M.</option>
                                            <option <?php if (isset($contact_sexe)  and $contact_sexe == 'Mme') echo "selected='selected'" ?>>Mme</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="newnom_contact" class="form-label required">Nom contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" placeholder="Nom contact" name="newnom_contact" id="newnom_contact" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="newprenom_contact" class="form-label required">Prénom contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" placeholder="Prénom contact" name="newprenom_contact" id="newprenom_contact" class="form-control">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="newmail_contact" class="form-label">Mail personnel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="email" placeholder="Mail personnel contact" name="newmail_contact" id="newmail_contact" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="newmailpro_contact" class="form-label">Mail professionel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="email" placeholder="Mail professionel contact" name="newmailpro_contact" id="newmailpro_contact" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="newtel_contact" class="form-label">Tél personnel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                            </div>
                                            <input type="tel" minlength="14" maxlength="14" placeholder="Tél personnel contact" name="newtel_contact" id="newtel_contact" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="newtelpro_contact" class="form-label">Tél professionel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                            </div>
                                            <input type="tel" minlength="14" maxlength="14" placeholder="Tél professionel contact" name="newtelpro_contact" id="newtelpro_contact" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="newportable_contact" class="form-label">Portable personnel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                            </div>
                                            <input type="tel" minlength="14" maxlength="14" placeholder="Portable personnel contact" name="newportable_contact" id="newportable_contact" class="form-control" value="<?php if (isset($contact_portable)) echo $contact_portable ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="newportablepro_contact" class="form-label">Portable professionel contact</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                            </div>
                                            <input type="tel" minlength="14" maxlength="14" placeholder="Portable professionel contact" name="newportablepro_contact" id="newportablepro_contact" class="form-control" value="<?php if (isset($contact_portablepro)) echo $contact_portablepro ?>">
                                        </div>
                                    </div>
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
                                            if ($membre_id == $_SESSION['id'] or (isset($id_membre) and $membre_id == $id_membre)) {
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
                                        <option value='1' selected="selected">Oui</option>
                                        <option value='0' <?php if (isset($id_reponse) and !$id_reponse) echo "selected='selected'" ?>>Non</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="hide-me3">
                                    <label for="newcontenu" class="form-label">Contenu</label>
                                    <textarea class="form-control" id="newcontenu" name="newcontenu" rows="4" cols="50" placeholder="Contenu de l'entretien..."></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="newdateAppel" class="form-label required">Date entretien</label>
                                    <input type="date" name="newdateAppel" id="newdateAppel" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdateAppelTime" class="form-label required">Heure</label>
                                    <input type="time" name="newdateAppelTime" id="newdateAppelTime" class="form-control" value="<?php echo date('H:i') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="newdateRappel" class="form-label">Date de rappel</label>
                                    <input type="date" name="newdateRappel" id="newdateRappel" class="form-control" onchange="plageAuto()">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdateRappelTime" class="form-label" class="form-label required">Plage de rappel</label>
                                    <input type="time" name="newdateRappelTime" id="newdateRappelTime" class="form-control" onchange="timeAuto('newdateRappelTime', 'newdate2RappelTime')">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newdate2RappelTime" class="form-label" class="form-label required" style="color:white">Heure</label>
                                    <input type="time" name="newdate2RappelTime" id="newdate2RappelTime" class="form-control">
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_entretien" name="form_entretien">Ajouter</button>
                        </form>

                        <script>
                            function test() {
                                etab = document.getElementById('newetab').value
                                contact = document.getElementById('newcontact').value
                                window.open('ajouter_offre?id=' + etab + '&id_contact=' + contact + '', '_blank');
                            }

                            function test2() {
                                etab = document.getElementById('newetab').value
                                contact = document.getElementById('newcontact').value
                                window.open('ajouter_offre?id_demande=1&id=' + etab + '&id_contact=' + contact + '', '_blank');
                            }
                        </script>
<!--                         <a target="_blank" onclick="test()">ajouter offre</a>
                        <a target="_blank" onclick="test2()">ajouter demande</a> -->
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