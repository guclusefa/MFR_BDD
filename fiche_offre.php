<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_ficheOffre = $_GET['id'];
            $requete_offre = $pdo->prepare("SELECT * FROM offres_alternances WHERE offreAlt_id = ?");
            $requete_offre->execute(array($id_ficheOffre));
            $requete_offre = $requete_offre->fetch();

            $id_etab = $requete_offre['offreAlt_idEtab'];
            $id_site = $requete_offre['offreAlt_idSite'];
            $id_contact = $requete_offre['offreAlt_idContact'];
            $id_formation = $requete_offre['offreAlt_idFormation'];
            $id_anneFormation = $requete_offre['offreAlt_anneeFormation'];
            $eleve_nom = $requete_offre['offreAlt_nom'];
            $eleve_prenom = $requete_offre['offreAlt_prenom'];
            $id_debut = $requete_offre['offreAlt_debut'];
            $id_fin = $requete_offre['offreAlt_fin'];
            $id_offre_alt = $requete_offre['offreAlt_idAlt'];
            $eleve_mailperso = $requete_offre['offreAlt_mailperso'];
            $eleve_mailpro = $requete_offre['offreAlt_mailpro'];
            $eleve_telperso = $requete_offre['offreAlt_telperso'];
            $eleve_telpro = $requete_offre['offreAlt_telpro'];
            $id_demande = $requete_offre['offreAlt_demande'];
            $id_raison = $requete_offre['offreAlt_raison'];

            if ($id_demande) {
                $offre = "demande";
                $offre2 = "Demande";
                $offre3 = "demandes";
            } else {
                $offre = "offre";
                $offre2 = "Offre";
                $offre3 = "offres";
            }
            $id_actif = $requete_offre['offreAlt_idAlt'];
        }
        if (isset($_GET['id_etab'])) {
            $id_etab = $_GET['id_etab'];
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $id_site", "site_idEtab");
        }

        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newanneeFor']) and !empty($_POST['newDateDebut']) and !empty($_POST['newDateFin'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newsite = htmlentities($_POST['newsite']);
                $newformation = htmlentities($_POST['newformation']);
                $newanneefor = htmlentities($_POST['newanneeFor']);
                $newdateDebut = htmlentities($_POST['newDateDebut']);
                $newdateFin = htmlentities($_POST['newDateFin']);
                $newactif = htmlentities($_POST['newactif']);
                $newcontact = htmlentities($_POST['newcontact']);
                $newraison = htmlentities($_POST['newraison']);

                $newnom = strtoupper(htmlentities($_POST['newnom']));
                $newprenom = ucwords(htmlentities($_POST['newprenom']));
                $newtel = htmlentities($_POST['newtel']);
                $newtelpro = htmlentities($_POST['newtelpro']);
                $newmail = htmlentities($_POST['newmail']);
                $newmailpro = htmlentities($_POST['newmailpro']);

                $insertetab = $pdo->prepare("UPDATE offres_alternances SET offreAlt_idEtab = ?, offreAlt_idFormation = ?, offreAlt_anneeFormation = ?, 	offreAlt_nom = ?, offreAlt_prenom = ?, offreAlt_debut = ?, offreAlt_fin = ?, offreAlt_idSite = ?, offreAlt_idContact = ?, offreAlt_mailperso = ?, offreAlt_mailpro = ?, offreAlt_telperso = ?, offreAlt_telpro = ?, offreAlt_raison = ?, offreAlt_idAlt = '0' WHERE offreAlt_id = ?");
                $insertetab->execute(array($newetab, $newformation, $newanneefor, $newnom, $newprenom, $newdateDebut, $newdateFin, $newsite, $newcontact, $newmail, $newmailpro, $newtel, $newtelpro, $newraison, $id_ficheOffre));
                $_SESSION['succes'] = "$offre2 d'alternance modifiée avec succées !";

                if ($newactif == 1 and $id_actif <= 0) {
                    if (ctype_space($newnom) and ctype_space($neweleve_prenom)) {
                        $_SESSION['erreur'] = "Remplir le nom et prénom !";
                        header("location: fiche_offre?id=$id_ficheOffre");
                        exit();
                    }
                    $insert_eleve = $pdo->prepare("INSERT INTO eleves(eleve_nom, eleve_prenom, eleve_mailperso, eleve_mailpro, eleve_telperso, eleve_telpro) VALUES(?,?,?,?,?,?)");
                    $insert_eleve->execute(array($newnom, $newprenom, $newmail, $newmailpro, $newtel, $newtelpro));

                    $id_new_eleve = requeteSQL($pdo, "SELECT * FROM eleves ORDER BY eleve_id DESC", "eleve_id");

                    $insert_alt = $pdo->prepare("INSERT INTO alternances(alt_idEleve, alt_idEtab, alt_idFormation, alt_anneeFormation, alt_debut, alt_fin, alt_actif, alt_idSite, alt_idContact) VALUES(?,?,?,?,'$newdateDebut','$newdateFin',?,?,?)");
                    $insert_alt->execute(array($id_new_eleve, $newetab, $newformation, $newanneefor, '1', $newsite, $newcontact));

                    $id_new_alt = requeteSQL($pdo, "SELECT * FROM alternances ORDER BY alt_id DESC", "alt_id");

                    $insertetab = $pdo->prepare("UPDATE offres_alternances SET offreAlt_idEtab = ?, offreAlt_idFormation = ?, offreAlt_anneeFormation = ?, 	offreAlt_nom = ?, offreAlt_prenom = ?, offreAlt_debut = ?, offreAlt_fin = ?, offreAlt_idAlt = ?, offreAlt_idSite = ? WHERE offreAlt_id = ?");
                    $insertetab->execute(array($newetab, $newformation, $newanneefor, $newnom, $newprenom, "$newdateDebut", "$newdateFin", $id_new_alt, $newsite, $id_ficheOffre));
                    $_SESSION['succes'] = "$sql_test $offre2 d'alternance acceptée ajoutée avec succées !";
                }
                if ($newactif == 2) {
                    $insertetab = $pdo->prepare("UPDATE offres_alternances SET offreAlt_idEtab = ?, offreAlt_idFormation = ?, offreAlt_anneeFormation = ?, 	offreAlt_nom = ?, offreAlt_prenom = ?, offreAlt_debut = ?, offreAlt_fin = ?, offreAlt_idAlt = '-1', offreAlt_idSite = ? WHERE offreAlt_id = ?");
                    $insertetab->execute(array($newetab, $newformation, $newanneefor, $newnom, $newprenom, $newdateDebut, $newdateFin, $newsite, $id_ficheOffre));
                    $_SESSION['succes'] = "$offre2 d'alternance refusée ajoutée avec succées !";
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: fiche_offre?id=$id_ficheOffre");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&$offre3=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Fiche <?php echo $offre ?></title>
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
                        <h1>Fiche <?php echo $offre ?> d'alternance</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="fiche_etablissement?id=<?php echo $id_etab ?>"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='fiche_offre?id=<?php echo $id_ficheOffre ?>&id_etab='+this.value">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements") as $unEtab) {
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
                                    foreach (lireLesUsers($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_idEtab = '$id_etab'") as $unSite) {
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
                                                if ($id_formation == $id_form) {
                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                } else {
                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="newanneeFor" class="form-label required">Année</label>
                                    <input class="form-control" type="number" id="newanneeFor" name="newanneeFor" min="1" max="3" value="<?php if (isset($id_anneFormation)) echo $id_anneFormation ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newDateDebut" class="form-label required">Date début</label>
                                    <input type="date" name="newDateDebut" id="newDateDebut" class="form-control" value="<?php if (isset($id_debut)) echo $id_debut ?>">
                                </div>
                                <div class=" form-group col-md-6">
                                    <label for="newDateFin" class="form-label required">Date fin</label>
                                    <input type="date" name="newDateFin" id="newDateFin" class="form-control" value="<?php if (isset($id_fin)) echo $id_fin ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newnom" class="form-label required">Nom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Nom" name="newnom" id="newnom" class="form-control" value="<?php if (isset($eleve_nom)) echo $eleve_nom ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newprenom" class="form-label required">Prénom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Prénom" name="newprenom" id="newprenom" class="form-control" value="<?php if (isset($eleve_prenom)) echo $eleve_prenom ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newmail" class="form-label">Mail personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $eleve_mailperso ?>"><i class="fas fa-envelope"></i></a></span>
                                        </div>
                                        <input type="email" placeholder="Mail personnel" name="newmail" id="newmail" class="form-control" value="<?php if (isset($eleve_mailperso)) echo $eleve_mailperso ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newmailpro" class="form-label">Mail professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $eleve_mailpro ?>"><i class="fas fa-envelope"></i></a></span>
                                        </div>
                                        <input type="email" placeholder="Mail professionel" name="newmailpro" id="newmailpro" class="form-control" value="<?php if (isset($eleve_mailpro)) echo $eleve_mailpro ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newtel" class="form-label">Tél personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél personnel" name="newtel" id="newtel" class="form-control" value="<?php if (isset($eleve_telperso)) echo $eleve_telperso ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newtelpro" class="form-label">Tél professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél professionel" name="newtelpro" id="newtelpro" class="form-control" value="<?php if (isset($eleve_mailperso)) echo $eleve_telpro ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newactif" class="form-label required">État <?php echo $offre ?></label>
                                <select name="newactif" id="newactif" class="form-control">
                                    <option value="0" <?php if (isset($id_actif) and $id_actif == 0) echo "selected='selected'" ?>>En attente</option>
                                    <option value="1" <?php if (isset($id_actif) and $id_actif) echo "selected='selected'" ?>>Acceptée</option>
                                    <option value="2" <?php if (isset($id_actif) and $id_actif == -1) echo "selected='selected'" ?>>Refusée</option>
                                </select>
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

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer cette <?php echo $offre ?> d'alternance</button>


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
                                        Êtes-vous sûr de vouloir supprimer cette <?php echo $offre ?> d'alternance et toutes les données qui lui sont liées ?<br><br>
                                        Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <input type="hidden" name="id_offre_alt" value="<?php echo $id_offre_alt ?>" />
                                            <input type="hidden" name="id_demande" value="<?php echo $id_demande ?>" />
                                            <button name="id_offre" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
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