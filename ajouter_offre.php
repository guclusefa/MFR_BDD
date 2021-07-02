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
        }

        if (isset($_GET['id_demande']) and $_GET['id_demande'] == 1) {
            $id_demande = 1;
            $offre3 = "demandes";
            $titre = "Ajouter une demande d'alternance";
        } else {
            $id_demande = 0;
            $offre3 = "offres";
            $titre = "Ajouter une offre d'alternance";
        }
        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newanneeFor']) and !empty($_POST['newDateDebut']) and !empty($_POST['newDateFin'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newformation = htmlentities($_POST['newformation']);
                $neweleve_nom = strtoupper(htmlentities($_POST['neweleve_nom']));
                $neweleve_prenom = ucwords(htmlentities($_POST['neweleve_prenom']));
                $newanneefor = htmlentities($_POST['newanneeFor']);
                $newdateDebut = htmlentities($_POST['newDateDebut']);
                $newdateFin = htmlentities($_POST['newDateFin']);
                $newsite = htmlentities($_POST['newsite']);
                $newcontact = htmlentities($_POST['newcontact']);
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

                for ($i = 0; $i < $_POST['newnumber']; $i++) {
                    $insertetab = $pdo->prepare("INSERT INTO offres_alternances(offreAlt_idEtab, offreAlt_idFormation, offreAlt_anneeFormation, offreAlt_nom, offreAlt_prenom, offreAlt_debut, offreAlt_fin, offreAlt_idAlt, offreAlt_demande, offreAlt_idSite, offreAlt_idContact) VALUES(?,?,?,?,?,?,?,0,?,?,?)");
                    $insertetab->execute(array($newetab, $newformation, $newanneefor, $neweleve_nom, $neweleve_prenom, $newdateDebut, $newdateFin, $id_demande, $newsite, $newcontact));
                }

                $_SESSION['succes'] = "Offre d'alternance ajoutée avec succès";
                if ($id_demande) {
                    $_SESSION['succes'] = "Demande d'alternance ajoutée avec succès";
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: ajouter_offre?id=$id_etab&id_demande=$id_demande");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&$offre3=1");
            exit();
        }
?>

        <head>
            <title><?php echo ("MFR BDD - $titre") ?></title>
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
                        <h1><?php echo ($titre) ?></h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='ajouter_offre?id_demande=<?php echo $id_demande ?>&id='+this.value">
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
                                <label for="newsite" class="form-label required">Site</label>
                                <select name="newsite" id="newsite" class="form-control">
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
                                <label for="newcontact" class="form-label required">Tuteur</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickContact()"><i class="fas fa-user-tie"></i></a></span>
                                    </div>
                                    <select name="newcontact" id="newcontact" class="form-control">
                                        <option value="0">Choisir un tuteur</option>
                                        <option value="-1">Nouveau contact</option>
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_idEtab = '$id_etab' ORDER BY contact_nom, contact_prenom") as $unContact) {
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

                            <div class="form-row">
                                <div class="form-group col-md-10">
                                    <label for="newformation" class="form-label required">Formation</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-graduation-cap"></i></span>
                                        </div>
                                        <select name="newformation" id="newformation" class="form-control">
                                            <?php
                                            foreach (lireLesUsers($pdo, "SELECT * FROM formations") as $uneForm) {
                                                $id_form = $uneForm['formation_id'];
                                                $nom_form = $uneForm['formation_niv'] . " " . $uneForm['formation_intitule'];
                                                echo ("<option value='$id_form'>$nom_form</option>");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newanneeFor" class="form-label required">Année</label>
                                    <input class="form-control" type="number" id="newanneeFor" name="newanneeFor" min="1" max="3" value="1">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newDateDebut" class="form-label required">Date début</label>
                                    <input type="date" name="newDateDebut" id="newDateDebut" class="form-control" value="<?php echo date('Y-09-01') ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newDateFin" class="form-label required">Date fin</label>
                                    <input type="date" name="newDateFin" id="newDateFin" class="form-control" value="<?php echo date("$date_annee_2-08-31") ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="newnumber" class="form-label required">Combien</label>
                                    <input type="number" name="newnumber" id="newnumber" class="form-control" value="1" min="1">
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