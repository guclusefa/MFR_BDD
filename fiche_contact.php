<!DOCTYPE html>
<html lang="fr">

<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_contact = $_GET['id'];
            $requete_contact = $pdo->prepare("SELECT * FROM contacts WHERE contact_id = ?");
            $requete_contact->execute(array($id_contact));
            $requete_contact = $requete_contact->fetch();

            $contact_idEtab = $requete_contact['contact_idEtab'];
            $contact_idSite = $requete_contact['contact_idSite'];
            $contact_idFonction = $requete_contact['contact_idFonction'];
            $contact_nom = $requete_contact['contact_nom'];
            $contact_prenom = $requete_contact['contact_prenom'];
            $contact_nomprenom = $requete_contact['contact_nom'] . " " . $requete_contact['contact_prenom'];
            $contact_tel = $requete_contact['contact_tel'];
            $contact_mail = $requete_contact['contact_mail'];
            $contact_actif = $requete_contact['contact_actif'];
            $contact_raison = $requete_contact['contact_raison'];
            $contact_telpro = $requete_contact['contact_telpro'];
            $contact_mailpro = $requete_contact['contact_mailpro'];
            $contact_sexe = $requete_contact['contact_sexe'];

            $contact_portable = $requete_contact['contact_portable'];
            $contact_portablepro = $requete_contact['contact_portablepro'];

            if (isset($_GET['id_etab'])) {
                $contact_idEtab = $_GET['id_etab'];
            }

            if (isset($_POST['form_eleve_form'])) {
                if (!empty($_POST['newnom']) or !empty($_POST['newprenom'])) {
                    $newetab = htmlentities($_POST['newetab']);
                    $newsite = htmlentities($_POST['newsite']);
                    $newfonction = strtoupper(htmlentities($_POST['newfonction']));
                    $newnom = strtoupper(htmlentities($_POST['newnom']));
                    $newprenom = ucwords(htmlentities($_POST['newprenom']));
                    $newtel = htmlentities($_POST['newtel']);
                    $newmail = htmlentities($_POST['newmail']);
                    $newactif = htmlentities($_POST['newactif']);
                    $newpk = htmlentities($_POST['newpk']);
                    $newportable = htmlentities($_POST['newportable']);
                    $newportablepro = htmlentities($_POST['newportablepro']);

                    $newtelpro = htmlentities($_POST['newtelpro']);
                    $newmailpro = htmlentities($_POST['newmailpro']);

                    $newsexe = htmlentities($_POST['newsexe']);

                    $insertcontact = $pdo->prepare("UPDATE contacts SET contact_idEtab = ?, contact_idSite = ?, contact_idFonction = ?, contact_nom = ?, contact_prenom = ?, contact_tel = ?, contact_mail = ?, contact_actif = ?, contact_raison = ?, contact_telpro = ?, contact_mailpro = ?, contact_portable = ?, contact_portablepro = ?, contact_sexe = ? WHERE contact_id = $id_contact");
                    $insertcontact->execute(array($newetab, $newsite, $newfonction, $newnom, $newprenom, $newtel, $newmail, $newactif, $newpk, $newtelpro, $newmailpro, $newportable, $newportablepro, $newsexe));
                    $_SESSION['succes'] = "Contact modifié avec succès";
                } else {
                    $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                    header("location: fiche_contact?id=$id_contact");
                    exit();
                }
                header("location: fiche_etablissement?id=$newetab&contacts=1");
                exit();
            }
        }
?>

        <head>
            <title>MFR BDD - <?php echo $contact_nomprenom ?></title>
        </head>

        <body>
            <section id="tabs" class="project-tab">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-10">
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
                            <h1><?php echo "Fiche contact : $contact_nomprenom" ?></h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a style="font-size:xx-large;width:100%;" class="nav-item nav-link <?php if ((empty($_GET['sites']) and empty($_GET['alternants']) and empty($_GET['offres']) and empty($_GET['demandes']) and empty($_GET['contacts']) and empty($_GET['entretiens']) and empty($_GET['events'])) or !empty($_GET['infos'])) echo "active" ?>" id="nav-profile-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo "$contact_nomprenom" ?></a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['alternants'])) echo "active" ?>" id="nav-home-tab3" data-toggle="tab" href="#nav-home3" role="tab" aria-controls="nav-home3" aria-selected="false">Les alternances</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['offres'])) echo "active" ?>" id="nav-home-tab4" data-toggle="tab" href="#nav-home4" role="tab" aria-controls="nav-home4" aria-selected="false">Les offres</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['demandes'])) echo "active" ?>" id="nav-home-tab5" data-toggle="tab" href="#nav-home5" role="tab" aria-controls="nav-home5" aria-selected="false">Les demandes</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['entretiens'])) echo "active" ?>" id="nav-home-tab7" data-toggle="tab" href="#nav-home7" role="tab" aria-controls="nav-home7" aria-selected="false">Les entretiens</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['events'])) echo "active" ?>" id="nav-home-tab8" data-toggle="tab" href="#nav-home8" role="tab" aria-controls="nav-home8" aria-selected="false">Les évènements</a>
                                </div>
                            </nav>

                            <div class="tab-content" id="nav-tabContent">
                                <div class="text-nowrap tab-pane fade <?php if ((empty($_GET['alternants']) and empty($_GET['offres']) and empty($_GET['demandes']) and empty($_GET['entretiens']) and empty($_GET['events']))  or !empty($_GET['infos'])) echo "show active" ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <br>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="newetab" class="form-label required">Établissement</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon3"><a target="_blank" class="hover-logo" href="fiche_etablissement?id=<?php echo $contact_idEtab ?>"><i class="fas fa-building"></i></a></span>
                                                </div>
                                                <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='fiche_contact?id=<?php echo $id_contact ?>&id_etab='+this.value">
                                                    <?php
                                                    foreach (lireLesUsers($pdo, "SELECT * FROM etablissements") as $unEtab) {
                                                        $id_etab2 = $unEtab['etab_id'];
                                                        $raisonsocial = $unEtab['etab_raisonSocial'];
                                                        if ($id_etab2 == $contact_idEtab) {
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
                                                foreach (lireLesUsers($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_idEtab = '$contact_idEtab'") as $unSite) {
                                                    $id_etab_site = $unSite['site_id'];
                                                    $id_etab_site_nom = "Site " . $unSite['typeSite_intitule'] . " de " . $unSite['site_ville'] . " (" . $unSite['site_adresse'] . ")";
                                                    if ($id_etab_site == $contact_idSite) {
                                                        echo ("<option value='$id_etab_site' selected='selected'>$id_etab_site_nom</option>");
                                                    } else {
                                                        echo ("<option value='$id_etab_site'>$id_etab_site_nom</option>");
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="newfonction" class="form-label required">Fonction</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-user-tie"></i></span>
                                                </div>
                                                <select name="newfonction" id="newfonction" class="form-control">
                                                    <?php
                                                    foreach (lireLesUsers($pdo, "SELECT * FROM fonctions_contacts") as $uneFonction) {
                                                        $fonction_id = $uneFonction['fonction_id'];
                                                        $fonction_intitule = $uneFonction['fonction_intitule'];
                                                        if ($fonction_id == $contact_idFonction) {
                                                            echo ("<option value='$fonction_id' selected='selected'>$fonction_intitule</option>");
                                                        } else {
                                                            echo ("<option value='$fonction_id'>$fonction_intitule</option>");
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="newsexe" class="form-label required">Civilité</label>
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
                                                <label for="newnom" class="form-label required">Nom</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" placeholder="Nom" name="newnom" id="newnom" class="form-control" value="<?php if (isset($contact_nom)) echo $contact_nom ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="newprenom" class="form-label required">Prénom</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" placeholder="Prénom" name="newprenom" id="newprenom" class="form-control" value="<?php if (isset($contact_prenom)) echo $contact_prenom ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="newmail" class="form-label">Mail personnel</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $contact_mail ?>"><i class="fas fa-envelope"></i></a></span>
                                                    </div>
                                                    <input type="email" placeholder="Mail personnel" name="newmail" id="newmail" class="form-control" value="<?php if (isset($contact_mail)) echo $contact_mail ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="newmailpro" class="form-label">Mail professionel</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $contact_mailpro ?>"><i class="fas fa-envelope"></i></a></span>
                                                    </div>
                                                    <input type="email" placeholder="Mail professionel" name="newmailpro" id="newmailpro" class="form-control" value="<?php if (isset($contact_mailpro)) echo $contact_mailpro ?>">
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
                                                    <input type="tel" minlength="14" maxlength="14" placeholder="Tél personnel" name="newtel" id="newtel" class="form-control" value="<?php if (isset($contact_tel)) echo $contact_tel ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="newtelpro" class="form-label">Tél professionel</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                                    </div>
                                                    <input type="tel" minlength="14" maxlength="14" placeholder="Tél professionel" name="newtelpro" id="newtelpro" class="form-control" value="<?php if (isset($contact_telpro)) echo $contact_telpro ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="newportable" class="form-label">Portable personnel</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                                    </div>
                                                    <input type="tel" minlength="14" maxlength="14" placeholder="Portable personnel" name="newportable" id="newportable" class="form-control" value="<?php if (isset($contact_portable)) echo $contact_portable ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="newportablepro" class="form-label">Portable professionel</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                                    </div>
                                                    <input type="tel" minlength="14" maxlength="14" placeholder="Portable professionel" name="newportablepro" id="newportablepro" class="form-control" value="<?php if (isset($contact_portablepro)) echo $contact_portablepro ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="newinteresse" class="form-label required">Accepte les appels</label>
                                            <select name="newactif" id="newinteresse" class="form-control">
                                                <option value='1'>Oui</option>
                                                <option value='0' <?php if (isset($contact_actif) and !$contact_actif) echo "selected='selected'" ?>>Non</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <div <?php if (isset($contact_actif) and !$contact_actif) {
                                                        echo " ";
                                                    } else {
                                                        echo "id='hide-me'";
                                                    } ?>>
                                                <label for="newraison" class="form-label">Raison</label>
                                                <textarea class="form-control" id="newraison" name="newpk" rows="4" cols="50" placeholder="Pourquoi ce contact n'accepte pas les appels..."><?php if (isset($contact_raison)) echo $contact_raison ?></textarea>
                                            </div>
                                        </div>

                                        <br>
                                        <input type="hidden" name="infos" value="1">
                                        <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Modifier</button>
                                    </form>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['alternants'])) echo "show active" ?>" id="nav-home3" role="tabpanel" aria-labelledby="nav-home-tab3">
                                    <?php
                                    if (isset($_GET['formation']) and $_GET['formation'] != 0) {
                                        $id_formation = $_GET['formation'];
                                        $sql_formation = " AND alt_idFormation = '$id_formation'";
                                        $sql_formation2 = " AND offreAlt_idFormation = '$id_formation'";

                                        $get_formation = requeteSQL($pdo, "SELECT * FROM formations WHERE formation_id = '$id_formation'", "formation_niv") . " " . requeteSQL($pdo, "SELECT * FROM formations WHERE formation_id = '$id_formation'", "formation_intitule");
                                    } else {
                                        $id_formation = 0;
                                        $sql_formation = "";
                                        $sql_formation2 = "";
                                    }

                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                        $id_date = $_GET['date'];

                                        $sql_date = " AND '$id_date' BETWEEN year(alt_debut) AND year(alt_fin) AND year(alt_debut) != '$id_date'";
                                        $sql_date2 = " AND '$id_date' = year(offreAlt_debut) ";
                                    } else {
                                        $id_date = 0;
                                        $sql_date = "";
                                        $sql_date2 = "";
                                    }

                                    if (isset($_GET['situation']) and $_GET['situation'] != '0') {
                                        $id_situation = $_GET['situation'];
                                        if ($id_situation == 1) {
                                            $id_situation_nom = "Pas commencée";
                                            $sql_situation = " AND alt_debut > '$date' AND alt_fin > '$date' AND alt_actif = 1 ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 2) {
                                            $id_situation_nom = "1ère année";
                                            $sql_situation = "  AND alt_anneeFormation = 1 AND '$date'  BETWEEN alt_debut AND ADDDATE(alt_debut, INTERVAL 1 YEAR) AND alt_actif = 1 ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 3) {
                                            $id_situation_nom = "2ème année";
                                            $sql_situation = " AND ('$date' > ADDDATE(alt_debut, INTERVAL 1 YEAR) OR alt_anneeFormation = 2) AND '$date' BETWEEN alt_debut AND ADDDATE(alt_debut, INTERVAL 2 YEAR)  AND alt_fin >= '$date' AND alt_actif = 1 ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 4) {
                                            $id_situation_nom = "3ème année";
                                            $sql_situation = " AND ('$date' > ADDDATE(alt_debut, INTERVAL 2 YEAR) OR alt_anneeFormation = 3) AND '$date'  BETWEEN alt_debut AND ADDDATE(alt_debut, INTERVAL 3 YEAR) AND alt_fin >= '$date' AND alt_actif = 1 ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 5) {
                                            $id_situation_nom = "Finie";
                                            $sql_situation = " AND alt_fin < '$date' ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 6) {
                                            $id_situation_nom = "Rupture de contrat";
                                            $sql_situation = " AND alt_actif = 0 ";
                                            $sql_situation2 = "";
                                        } else if ($id_situation == 7) {
                                            $id_situation_nom = "En attente";
                                            $sql_situation = "";
                                            $sql_situation2 = " AND offreAlt_idAlt = 0  AND (offreAlt_nom = ' ' OR offreAlt_prenom = ' ') ";
                                        } else if ($id_situation == 8) {
                                            $id_situation_nom = "CV envoyé";
                                            $sql_situation = "";
                                            $sql_situation2 = " AND offreAlt_idAlt = 0  AND (offreAlt_nom != ' ' OR offreAlt_prenom != ' ') ";
                                        } else if ($id_situation == 9) {
                                            $id_situation_nom = "Acceptée";
                                            $sql_situation = "";
                                            $sql_situation2 = " AND offreAlt_idAlt != 0 AND offreAlt_idAlt != -1";
                                        } else if ($id_situation == 10) {
                                            $id_situation_nom = "Refusée";
                                            $sql_situation = "";
                                            $sql_situation2 = " AND offreAlt_idAlt = -1";
                                        }
                                    } else {
                                        $id_situation = 0;
                                        $sql_situation = "";
                                        $sql_situation2 = "";
                                    }

                                    if (isset($_GET['ordre'])) {
                                        if ($_GET['ordre']) {
                                            $ordre = " DESC";
                                        } else {
                                            $ordre = " ASC";
                                        }
                                    } else {
                                        $ordre = " ASC";
                                    }

                                    if (isset($_GET['trie']) and $_GET['trie'] != 0) {
                                        $get_trie = $_GET['trie'];
                                        if ($get_trie == 2) {
                                            $sql_trie_order = " ORDER BY  contact_nom, contact_prenom ";
                                            $sql_trie_order2 = " ORDER BY  contact_nom, contact_prenom ";
                                        } else if ($get_trie == 3) {
                                            $sql_trie_order = " ORDER BY  etab_raisonSocial ";
                                            $sql_trie_order2 = " ORDER BY  etab_raisonSocial ";
                                        } else if ($get_trie == 4) {
                                            $sql_trie_order = " ORDER BY formation_niv, formation_intitule ";
                                            $sql_trie_order2 = " ORDER BY formation_niv, formation_intitule ";
                                        }
                                    } else {
                                        $sql_trie_order = " ORDER BY eleve_nom, eleve_prenom ";
                                        $sql_trie_order2 = " ORDER BY offreAlt_nom, offreAlt_prenom ";
                                    }
                                    ?>

                                    <table class=" table table-striped sortable" cellspacing="0">
                                        <thead>
                                            <div>
                                                <form action="ajouter_alternance.php">
                                                    <button name="id_contact" value="<?php echo $id_contact ?>" type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un(e) alternant(e)</button>
                                                </form>
                                            </div>

                                            <div class="margin_bottom20">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="alternants" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <div class="input-group mb-3">
                                                        <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un(e) alternant(e)..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                        <datalist id="alternants">
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM eleves, offres_alternances GROUP BY eleve_nom, eleve_prenom  ORDER BY eleve_nom, eleve_prenom ") as $unEleve) {
                                                                $eleve_nom = $unEleve['eleve_nom'];
                                                                $eleve_prenom = $unEleve['eleve_prenom'];
                                                                echo ("<option value='$eleve_nom $eleve_prenom'>");
                                                            }
                                                            ?>
                                                        </datalist>
                                                        <?php if (empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search"><?php if (empty($_GET['search'])) echo 'Chercher' ?><?php if (!empty($_GET['search'])) echo '&times;'; ?></button>
                                                            </div>
                                                        <?php } ?>
                                                </form>

                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="alternants" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php if (!empty($_GET['search'])) { ?>
                                                        <div class="input-group-prepend">
                                                            <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                        </div>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                            <div>
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="alternants" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">


                                                    <div class="titre_tableau">
                                                        <select class="form-control select_etab select_etab_gauche" name="formation" onchange="this.form.submit()">
                                                            <option value="0">Choisir une formation</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM formations") as $uneForm) {
                                                                $id_form = $uneForm['formation_id'];
                                                                $nom_form = $uneForm['formation_niv'] . " " . $uneForm['formation_intitule'];
                                                                if (!empty($_GET['formation']) and $_GET['formation'] == $id_form) {
                                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                } else {
                                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="date" onchange="this.form.submit()">
                                                            <option value="0">Choisir une année scolaire</option>
                                                            <?php
                                                            for ($i = 2010; $i < 2050 + 1; $i++) {
                                                                if (!empty($_GET['date']) and $_GET['date'] == $i) {
                                                                    echo ("<option selected='selected'>$i</option>");
                                                                } else {
                                                                    echo ("<option>$i</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="situation" onchange="this.form.submit()">
                                                            <option value="0">Choisir une situation</option>
                                                            <option value="1" <?php if (isset($_GET['situation']) and $_GET['situation'] == 1) echo 'selected=selected' ?>>Pas commencée</option>
                                                            <option value="2" <?php if (isset($_GET['situation']) and $_GET['situation'] == 2) echo 'selected=selected' ?>>1ère année</option>
                                                            <option value="3" <?php if (isset($_GET['situation']) and $_GET['situation'] == 3) echo 'selected=selected' ?>>2ème année</option>
                                                            <option value="4" <?php if (isset($_GET['situation']) and $_GET['situation'] == 4) echo 'selected=selected' ?>>3ème année</option>
                                                            <option value="5" <?php if (isset($_GET['situation']) and $_GET['situation'] == 5) echo 'selected=selected' ?>>Finie</option>
                                                            <option value="6" <?php if (isset($_GET['situation']) and $_GET['situation'] == 6) echo 'selected=selected' ?>>Rupture de contrat</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="titre_tableau">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">
                                                    <input type="hidden" name="alternants" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php
                                                    if (isset($_GET['formation']) and $_GET['formation'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="formation" value="0">
                                                            <span aria-hidden="true"><?php echo $get_formation ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="date" value="0">
                                                            <span aria-hidden="true"><?php echo $id_date ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if (isset($_GET['situation']) and $_GET['situation'] != '0' and $_GET['situation'] <= 6) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="situation" value="0">
                                                            <span aria-hidden="true"><?php echo $id_situation_nom ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>
                                                </form>
                                            </div>

                                            <?php
                                            try {
                                                $sql_alt_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_idContact = $id_contact " . $sql_formation . $sql_date . $sql_situation, "total");
                                                if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                    $search = htmlentities($_GET['search']);
                                                    $sql_alt_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances, etablissements, eleves WHERE etab_id = alt_idEtab  AND alt_idContact = $id_contact AND eleve_id = alt_idEleve AND (etab_raisonSocial LIKE '%$search%' OR eleve_nom LIKE '%$search%' OR eleve_prenom LIKE '%$search%') " . $sql_formation . $sql_date . $sql_situation, "total");
                                                    $msg_search = " pour '<b>$search</b>'";
                                                } else {
                                                    $msg_search = "";
                                                }
                                                if ($sql_alt_count) {
                                            ?>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Tuteur</th>
                                                        <th>Formation</th>
                                                        <th>Début</th>
                                                        <th>Fin</th>
                                                        <th>Situation</th>
                                                        <th>Fiche</th>
                                                    </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                                    if ($sql_alt_count == 1) {
                                                        echo "<h1 class='titre_tableau'>$sql_alt_count alternant(e) $msg_search</h1>";
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>$sql_alt_count alternant(e)s $msg_search</h1>";
                                                    }
                                                    $sql_alt = "SELECT *  FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idEleve = eleve_id AND alt_idFormation = formation_id AND etab_id = alt_idEtab AND alt_idContact = $id_contact " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC";
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_alt = "SELECT * FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idFormation = formation_id AND alt_idEleve = eleve_id AND etab_id = alt_idEtab AND alt_idContact = $id_contact AND (eleve_prenom LIKE '%$search%' OR eleve_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC";
                                                    }
                                                    $lesEleves = lireLesUsers($pdo, $sql_alt);
                                                    foreach ($lesEleves as $unEleve) {
                                                        $id_alt = $unEleve['alt_id'];
                                                        $id_etab = $unEleve['alt_idEtab'];
                                                        $id_etab_nom = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $id_etab", "etab_raisonSocial");
                                                        $id_eleve = $unEleve['eleve_id'];
                                                        $nom_eleve = $unEleve['eleve_nom'] . " " . $unEleve['eleve_prenom'];
                                                        $nom_formation = $unEleve['formation_niv'] . " " . $unEleve['formation_intitule'];
                                                        $anneeFor = $unEleve['alt_anneeFormation'];
                                                        $dateDebut = $unEleve['alt_debut'];
                                                        $dateFin = $unEleve['alt_fin'];
                                                        $site_id = $unEleve['alt_idSite'];
                                                        $alt_contact_id = $unEleve['alt_idContact'];
                                                        $alt_actif = $unEleve['alt_actif'];

                                                        if ($site_id == 0) {
                                                            $site_nom = "</a>Site Principal";
                                                        } else {
                                                            $site_nom = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_id = '$site_id'", "typeSite_intitule");
                                                        }

                                                        $contact_nom = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $alt_contact_id", "contact_nom") . " " . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $alt_contact_id", "contact_prenom");
                                                        if ($alt_contact_id == 0) {
                                                            $contact_nom = "</a>Pas précisé";
                                                        }

                                                        if ($date < $dateDebut) {
                                                            $situation = "Pas commencée";
                                                            $class_td = "table-primary";
                                                        }
                                                        if ($date > $dateFin) {
                                                            $situation = "Finie";
                                                            $class_td = "table-success";
                                                        }
                                                        if ($dateDebut <= $date  and $date <= $dateFin) {
                                                            $annee1 = date('Y-m-d', strtotime("+1 year", strtotime($dateDebut)));
                                                            $annee2 = date('Y-m-d', strtotime("+2 years", strtotime($dateDebut)));
                                                            $annee3 = date('Y-m-d', strtotime("+3 years", strtotime($dateDebut)));
                                                            if ($date <= $annee1 and $anneeFor == 1) {
                                                                $situation = "1ère année";
                                                                $class_td = "";
                                                            } else if ($date <= $annee2 and ($anneeFor == 1 or $anneeFor == 2)) {
                                                                $situation = "2ème année";
                                                                $class_td = "";
                                                            } else if ($date <= $annee3) {
                                                                $situation = "3ème année";
                                                                $class_td = "";
                                                            }
                                                        }
                                                        if (!$alt_actif) {
                                                            $situation = "Rupture de contrat";
                                                            $class_td = "table-danger";
                                                        }

                                                        $dateDebut = date('Y', strtotime($dateDebut));
                                                        $dateFin = date('Y', strtotime($dateFin));

                                                        echo ("<td><a href='fiche_eleve?id=$id_eleve'>$nom_eleve</td>
                                                <td><a href='fiche_contact?id=$alt_contact_id'>$contact_nom</a></td>
                                                <td>$nom_formation</td>
                                                <td>$dateDebut</td>
                                                <td>$dateFin</td>
                                                <td class=$class_td>$situation</td>
                                                <td><a href='fiche_alternance?id=$id_alt'>Fiche</td></tr>");
                                                    }
                                                } else {
                                                    echo "<h1 class='titre_tableau'>Aucun(e) alternant(e) $msg_search</h1>";
                                                }
                                            } catch (Exception $e) {
                                                die("Ereur grave : " . $e->getMessage());
                                            }
                                    ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['offres'])) echo "show active" ?>" id="nav-home4" role="tabpanel" aria-labelledby="nav-home-tab4">
                                    <table class=" table table-striped sortable" cellspacing="0">
                                        <thead>
                                            <div>
                                                <form action="ajouter_offre">
                                                    <button name="id_contact" value="<?php echo $id_contact ?>" type="submit" class="btn btn-primary button_w100 margin_30">Ajouter une offre</button>
                                                </form>
                                            </div>

                                            <div class="margin_bottom20">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="offres" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <div class="input-group mb-3">
                                                        <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher une offre..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                        <datalist id="alternants">
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM eleves, offres_alternances GROUP BY eleve_nom, eleve_prenom  ORDER BY eleve_nom, eleve_prenom ") as $unEleve) {
                                                                $eleve_nom = $unEleve['eleve_nom'];
                                                                $eleve_prenom = $unEleve['eleve_prenom'];
                                                                echo ("<option value='$eleve_nom $eleve_prenom'>");
                                                            }
                                                            ?>
                                                            <?php if (empty($_GET['search'])) { ?>
                                                                <div class="input-group-prepend">
                                                                    <button type="submit" class="input-group-text cursor" id="search"><?php if (empty($_GET['search'])) echo 'Chercher' ?><?php if (!empty($_GET['search'])) echo '&times;'; ?></button>
                                                                </div>
                                                            <?php } ?>
                                                </form>

                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="offres" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php if (!empty($_GET['search'])) { ?>
                                                        <div class="input-group-prepend">
                                                            <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                        </div>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                            <div>
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="offres" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">



                                                    <div class="titre_tableau">
                                                        <select class="form-control select_etab select_etab_gauche" name="formation" onchange="this.form.submit()">
                                                            <option value="0">Choisir une formation</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM formations") as $uneForm) {
                                                                $id_form = $uneForm['formation_id'];
                                                                $nom_form = $uneForm['formation_niv'] . " " . $uneForm['formation_intitule'];
                                                                if (!empty($_GET['formation']) and $_GET['formation'] == $id_form) {
                                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                } else {
                                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="date" onchange="this.form.submit()">
                                                            <option value="0">Choisir une année scolaire</option>
                                                            <?php
                                                            for ($i = 2010; $i < 2050 + 1; $i++) {
                                                                if (!empty($_GET['date']) and $_GET['date'] == $i) {
                                                                    echo ("<option selected='selected'>$i</option>");
                                                                } else {
                                                                    echo ("<option>$i</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="situation" onchange="this.form.submit()">
                                                            <option value="0">Choisir un état</option>
                                                            <option value="7" <?php if (isset($_GET['situation']) and $_GET['situation'] == 7) echo 'selected=selected' ?>>En attente</option>
                                                            <option value="8" <?php if (isset($_GET['situation']) and $_GET['situation'] == 8) echo 'selected=selected' ?>>CV envoyé</option>
                                                            <option value="9" <?php if (isset($_GET['situation']) and $_GET['situation'] == 9) echo 'selected=selected' ?>>Acceptée</option>
                                                            <option value="10" <?php if (isset($_GET['situation']) and $_GET['situation'] == 10) echo 'selected=selected' ?>>Refusée</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="titre_tableau">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="offres" value="1">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php
                                                    if (isset($_GET['formation']) and $_GET['formation'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="formation" value="0">
                                                            <span aria-hidden="true"><?php echo $get_formation ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="date" value="0">
                                                            <span aria-hidden="true"><?php echo $id_date ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if (isset($_GET['situation']) and $_GET['situation'] != '0' and $_GET['situation'] > 6) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="situation" value="0">
                                                            <span aria-hidden="true"><?php echo $id_situation_nom ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>
                                                </form>
                                            </div>

                                            <?php
                                            try {
                                                $sql_offre_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND offreAlt_idContact = $id_contact " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                    $search = htmlentities($_GET['search']);
                                                    $sql_offre_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND offreAlt_idContact = $id_contact AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                    $msg_search = " pour '<b>$search</b>'";
                                                } else {
                                                    $msg_search = "";
                                                }
                                                if ($sql_offre_count) {
                                            ?>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Tuteur</th>
                                                        <th>Formation</th>
                                                        <th>Début</th>
                                                        <th>Fin</th>
                                                        <th>État</th>
                                                        <th>Fiche</th>
                                                    </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                                    if ($sql_offre_count == 1) {
                                                        echo "<h1 class='titre_tableau'>$sql_offre_count offre d'alternance $msg_search</h1>";
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>$sql_offre_count offres d'alternances $msg_search</h1>";
                                                    }
                                                    $sql_offre = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND offreAlt_idContact = $id_contact " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC";
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_offre = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND offreAlt_idContact = $id_contact AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC";
                                                    }
                                                    $lesOffres = lireLesUsers($pdo, $sql_offre);
                                                    foreach ($lesOffres as $uneOffre) {
                                                        $id_offre = $uneOffre['offreAlt_id'];
                                                        $id_offre_etab = $uneOffre['offreAlt_idEtab'];
                                                        $id_offre_etab_rs = $uneOffre['etab_raisonSocial'];
                                                        $id_offre_formation = $uneOffre['formation_niv'];
                                                        $id_offre_anneeFormation = $uneOffre['offreAlt_anneeFormation'];
                                                        $id_offre_nom = $uneOffre['offreAlt_nom'] . " " . $uneOffre['offreAlt_prenom'];
                                                        if (ctype_space($id_offre_nom)) {
                                                            $id_offre_nom = "Personne";
                                                        }
                                                        $id_offre_debut = $uneOffre['offreAlt_debut'];
                                                        $id_offre_fin = $uneOffre['offreAlt_fin'];
                                                        $site_id = $uneOffre['offreAlt_idSite'];
                                                        if ($site_id == 0) {
                                                            $site_nom = "</a>Site Principal";
                                                        } else {
                                                            $site_nom = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_id = '$site_id'", "typeSite_intitule");
                                                        }
                                                        $offre_contact_id = $uneOffre['offreAlt_idContact'];
                                                        $contact_nom = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $offre_contact_id", "contact_nom") . " " . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $offre_contact_id", "contact_prenom");
                                                        if ($offre_contact_id == 0) {
                                                            $contact_nom = "</a>Pas précisé";
                                                        }

                                                        $id_offre_idAlt = $uneOffre['offreAlt_idAlt'];
                                                        if ($id_offre_idAlt == 0 and $id_offre_nom == "Personne") {
                                                            $parvenu = "<td class='table-warning'>En attente</td>";
                                                        } else if ($id_offre_idAlt == 0 and $id_offre_nom != "Personne") {
                                                            $parvenu = "<td class='table-primary'>CV envoyé</td>";
                                                        } else if ($id_offre_idAlt == -1) {
                                                            $parvenu = "<td class='table-danger''>Refusée</td>";
                                                        } else {
                                                            $parvenu = "<td class='table-success'><a href='fiche_alternance?id=$id_offre_idAlt'>Acceptée</td>";
                                                        }

                                                        $id_offre_debut = date('Y', strtotime($id_offre_debut));
                                                        $id_offre_fin = date('Y', strtotime($id_offre_fin));
                                                        echo ("<tr><td>$id_offre_nom</td>
                                                <td><a href='fiche_contact?id=$offre_contact_id'>$contact_nom</a></td>
                                                <td>$id_offre_formation</td>
                                                <td>$id_offre_debut</td>
                                                <td>$id_offre_fin</td>
                                                $parvenu
                                                <td><a href='fiche_offre?id=$id_offre'>Fiche</td></a></tr>");
                                                    }
                                                } else {
                                                    echo "<h1 class='titre_tableau'>Aucune offre d'alternance $msg_search</h1>";
                                                }
                                            } catch (Exception $e) {
                                                die("Ereur grave : " . $e->getMessage());
                                            }
                                    ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['demandes'])) echo "show active" ?>" id="nav-home5" role="tabpanel" aria-labelledby="nav-home-tab5">
                                    <table class=" table table-striped sortable" cellspacing="0">
                                        <thead>
                                            <div>
                                                <form method="GET" action="ajouter_offre">
                                                    <input type="hidden" name="id_demande" value="1">
                                                    <button name="id_contact" value="<?php echo $id_contact ?>" type="submit" class="btn btn-primary button_w100 margin_30">Ajouter une demande</button>
                                                </form>
                                            </div>

                                            <div class="margin_bottom20">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="demandes" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <div class="input-group mb-3">
                                                        <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher une demande..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                        <datalist id="alternants">
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM eleves, offres_alternances GROUP BY eleve_nom, eleve_prenom  ORDER BY eleve_nom, eleve_prenom ") as $unEleve) {
                                                                $eleve_nom = $unEleve['eleve_nom'];
                                                                $eleve_prenom = $unEleve['eleve_prenom'];
                                                                echo ("<option value='$eleve_nom $eleve_prenom'>");
                                                            }
                                                            ?>
                                                        </datalist>
                                                        <?php if (empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search"><?php if (empty($_GET['search'])) echo 'Chercher' ?><?php if (!empty($_GET['search'])) echo '&times;'; ?></button>
                                                            </div>
                                                        <?php } ?>
                                                </form>

                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="demandes" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php if (!empty($_GET['search'])) { ?>
                                                        <div class="input-group-prepend">
                                                            <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                        </div>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                            <div>
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="demandes" value="1">
                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">



                                                    <div class="titre_tableau">
                                                        <select class="form-control select_etab select_etab_gauche" name="formation" onchange="this.form.submit()">
                                                            <option value="0">Choisir une formation</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM formations") as $uneForm) {
                                                                $id_form = $uneForm['formation_id'];
                                                                $nom_form = $uneForm['formation_niv'] . " " . $uneForm['formation_intitule'];
                                                                if (!empty($_GET['formation']) and $_GET['formation'] == $id_form) {
                                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                } else {
                                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="date" onchange="this.form.submit()">
                                                            <option value="0">Choisir une année scolaire</option>
                                                            <?php
                                                            for ($i = 2010; $i < 2050 + 1; $i++) {
                                                                if (!empty($_GET['date']) and $_GET['date'] == $i) {
                                                                    echo ("<option selected='selected'>$i</option>");
                                                                } else {
                                                                    echo ("<option>$i</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="situation" onchange="this.form.submit()">
                                                            <option value="0">Choisir un état</option>
                                                            <option value="7" <?php if (isset($_GET['situation']) and $_GET['situation'] == 7) echo 'selected=selected' ?>>En attente</option>
                                                            <option value="8" <?php if (isset($_GET['situation']) and $_GET['situation'] == 8) echo 'selected=selected' ?>>CV envoyé</option>
                                                            <option value="9" <?php if (isset($_GET['situation']) and $_GET['situation'] == 9) echo 'selected=selected' ?>>Acceptée</option>
                                                            <option value="10" <?php if (isset($_GET['situation']) and $_GET['situation'] == 10) echo 'selected=selected' ?>>Refusée</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="titre_tableau">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="demandes" value="1">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                    <?php
                                                    if (isset($_GET['formation']) and $_GET['formation'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="formation" value="0">
                                                            <span aria-hidden="true"><?php echo $get_formation ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="date" value="0">
                                                            <span aria-hidden="true"><?php echo $id_date ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if (isset($_GET['situation']) and $_GET['situation'] != '0' and $_GET['situation'] > 6) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="situation" value="0">
                                                            <span aria-hidden="true"><?php echo $id_situation_nom ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>
                                                </form>
                                            </div>

                                            <?php
                                            try {
                                                $sql_demande_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND offreAlt_idContact = $id_contact " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                    $search = htmlentities($_GET['search']);
                                                    $sql_demande_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND offreAlt_idContact = $id_contact AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                    $msg_search = " pour '<b>$search</b>'";
                                                } else {
                                                    $msg_search = "";
                                                }
                                                if ($sql_demande_count) {
                                            ?>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Tuteur</th>
                                                        <th>Site</th>
                                                        <th>Formation</th>
                                                        <th>Début</th>
                                                        <th>Fin</th>
                                                        <th>État</th>
                                                        <th>Fiche</th>
                                                    </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                                    if ($sql_demande_count == 1) {
                                                        echo "<h1 class='titre_tableau'>$sql_demande_count demande d'alternance $msg_search</h1>";
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>$sql_demande_count demandes d'alternances $msg_search</h1>";
                                                    }
                                                    $sql_demande = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND offreAlt_idContact = $id_contact " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC";
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_demande = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND offreAlt_idContact = $id_contact AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC";
                                                    }
                                                    $lesOffres = lireLesUsers($pdo, $sql_demande);
                                                    foreach ($lesOffres as $uneOffre) {
                                                        $id_offre = $uneOffre['offreAlt_id'];
                                                        $id_offre_etab = $uneOffre['offreAlt_idEtab'];
                                                        $id_offre_etab_rs = $uneOffre['etab_raisonSocial'];
                                                        $id_offre_formation = $uneOffre['formation_niv'];
                                                        $id_offre_anneeFormation = $uneOffre['offreAlt_anneeFormation'];
                                                        $id_offre_nom = $uneOffre['offreAlt_nom'] . " " . $uneOffre['offreAlt_prenom'];
                                                        if (ctype_space($id_offre_nom)) {
                                                            $id_offre_nom = "Personne";
                                                        }
                                                        $id_offre_debut = $uneOffre['offreAlt_debut'];
                                                        $id_offre_fin = $uneOffre['offreAlt_fin'];
                                                        $site_id = $uneOffre['offreAlt_idSite'];
                                                        if ($site_id == 0) {
                                                            $site_nom = "</a>Site Principal";
                                                        } else {
                                                            $site_nom = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_id = '$site_id'", "typeSite_intitule");
                                                        }
                                                        $offre_contact_id = $uneOffre['offreAlt_idContact'];
                                                        $contact_nom = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $offre_contact_id", "contact_nom") . " " . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $offre_contact_id", "contact_prenom");
                                                        if ($offre_contact_id == 0) {
                                                            $contact_nom = "</a>Pas précisé";
                                                        }

                                                        $id_offre_idAlt = $uneOffre['offreAlt_idAlt'];
                                                        if ($id_offre_idAlt == 0 and $id_offre_nom == "Personne") {
                                                            $parvenu = "<td class='table-warning'>En attente</td>";
                                                        } else if ($id_offre_idAlt == 0 and $id_offre_nom != "Personne") {
                                                            $parvenu = "<td class='table-primary'>CV envoyé</td>";
                                                        } else if ($id_offre_idAlt == -1) {
                                                            $parvenu = "<td class='table-danger''>Refusée</td>";
                                                        } else {
                                                            $parvenu = "<td class='table-success'><a href='fiche_alternance?id=$id_offre_idAlt'>Acceptée</td>";
                                                        }

                                                        $id_offre_debut = date('Y', strtotime($id_offre_debut));
                                                        $id_offre_fin = date('Y', strtotime($id_offre_fin));
                                                        echo ("<tr><td>$id_offre_nom</td>
                                                <td><a href='fiche_contact?id=$offre_contact_id'>$contact_nom</a></td>
                                                <td><a href='fiche_site?id=$site_id'>$site_nom</a></td>
                                                <td>$id_offre_formation</td>
                                                <td>$id_offre_debut</td>
                                                <td>$id_offre_fin</td>
                                                $parvenu
                                                <td><a href='fiche_offre?id=$id_offre'>Fiche</td></a></tr>");
                                                    }
                                                } else {
                                                    echo "<h1 class='titre_tableau'>Aucune demande d'alternance $msg_search</h1>";
                                                }
                                            } catch (Exception $e) {
                                                die("Ereur grave : " . $e->getMessage());
                                            }
                                    ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['entretiens'])) echo "show active" ?>" id="nav-home7" role="tabpanel" aria-labelledby="nav-home-tab7">
                                    <?php
                                    if (isset($_GET['fonction']) and $_GET['fonction'] != 0) {
                                        $id_fonction = $_GET['fonction'];
                                        $sql_fonction = " AND contact_idFonction = '$id_fonction'";

                                        $get_fonction = requeteSQL($pdo, "SELECT * FROM fonctions_contacts WHERE fonction_id = '$id_fonction'", "fonction_intitule");
                                    } else {
                                        $id_fonction = 0;
                                        $sql_fonction = "";
                                    }

                                    if (isset($_GET['appel']) and $_GET['appel'] != 0) {
                                        $id_appel = $_GET['appel'];
                                        if ($id_appel == 2) {
                                            $id_appel = 0;
                                        }
                                        $sql_appel = " AND contact_actif = '$id_appel'";
                                        if ($_GET['appel'] == 1) {
                                            $get_appel = "Oui";
                                        } else {
                                            $get_appel = "Non";
                                        }
                                    } else {
                                        $id_appel = 0;
                                        $sql_appel = "";
                                    }



                                    if (isset($_GET['interlocuteur']) and $_GET['interlocuteur'] != 0) {
                                        $id_interlocuteur = $_GET['interlocuteur'];
                                        $sql_interlocuteur = " AND entretien_idMembre = '$id_interlocuteur' ";
                                        $get_interlocuteur = requeteSQL($pdo, "SELECT * FROM membres WHERE memb_id = '$id_interlocuteur'", "memb_nom") . " " . requeteSQL($pdo, "SELECT * FROM membres WHERE memb_id = '$id_interlocuteur'", "memb_prenom");
                                    } else {
                                        $id_interlocuteur = 0;
                                        $sql_interlocuteur = "";
                                    }

                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                        $id_date = $_GET['date'];
                                        $id_date2 = date('Y-m-d H:i:s', strtotime($id_date));
                                        $id_date3 = date('Y-m-d H:i:s', strtotime($id_date2 . " +1 days"));
                                        $sql_date = " AND entretien_dateAppel >= '$id_date2' AND entretien_dateAppel < '$id_date3'";

                                        $get_date = "Date d'appel : " . dateMySQLToFrLong($id_date);
                                    } else {
                                        $id_date = 0;
                                        $sql_date = "";
                                    }

                                    if (isset($_GET['date2']) and $_GET['date2'] != 0) {
                                        $id_date2 = $_GET['date2'];
                                        $sql_date2 = " AND CAST(entretien_dateRappel as date) = '$id_date2'";

                                        $get_date2 = "Date de rappel : " . dateMySQLToFrLong($id_date2);
                                    } else {
                                        $id_date2 = 0;
                                        $sql_date2 = "";
                                    }

                                    if (isset($_GET['reponse']) and $_GET['reponse'] != 0) {
                                        $id_reponse = $_GET['reponse'];
                                        if ($id_reponse == 2) {
                                            $id_reponse = 0;
                                        }
                                        $sql_reponse = " AND entretien_reponse = '$id_reponse'";
                                        if ($_GET['reponse'] == 1) {
                                            $get_reponse = "Oui";
                                        } else {
                                            $get_reponse = "Non";
                                        }
                                    } else {
                                        $id_reponse = 0;
                                        $sql_reponse = "";
                                    }

                                    if (isset($_GET['rappeler']) and $_GET['rappeler'] != 0) {
                                        $id_rappeler = $_GET['rappeler'];
                                        if ($id_rappeler == 2) {
                                            $id_rappeler = 0;
                                        }
                                        if ($_GET['rappeler'] == 1) {
                                            $get_rappeler = "À rappeler";
                                            $sql_rappeler = " AND '$dateSQL' <= entretien_dateRappel2 AND entretien_idNextEntretien = -1";
                                        } else if ($_GET['rappeler'] == 2) {
                                            $get_rappeler = "Rappel manqué";
                                            $sql_rappeler = " AND '$dateSQL' > entretien_dateRappel2 AND entretien_idNextEntretien = -1";
                                        } else {
                                            $get_rappeler = "Fini";
                                            $sql_rappeler = " AND entretien_idNextEntretien > -1";
                                        }
                                    } else {
                                        $id_rappeler = 0;
                                        $sql_rappeler = "";
                                    }



                                    if (isset($_GET['ordre'])) {
                                        if ($_GET['ordre']) {
                                            $ordre = " DESC";
                                            $ordre2 = " DESC";
                                        } else {
                                            $ordre = " ASC";
                                            $ordre2 = " ASC";
                                        }
                                    } else {
                                        $ordre = " ASC";
                                        $ordre2 = " DESC";
                                    }

                                    if (isset($_GET['trie']) and $_GET['trie'] != 0) {
                                        $get_trie = $_GET['trie'];
                                        if ($get_trie == 1) {
                                            $sql_trie_order = " ORDER BY contact_nom, contact_prenom ";
                                            $sql_trie_order2 = " ORDER BY contact_nom, contact_prenom ";
                                        } else if ($get_trie == 2) {
                                            $sql_trie_order = " ORDER BY etab_raisonSocial ";
                                            $sql_trie_order2 = " ORDER BY etab_raisonSocial ";
                                        } else if ($get_trie == 3) {
                                            $sql_trie_order = " ORDER BY etab_raisonSocial  ";
                                            $sql_trie_order2 = " ORDER BY memb_nom ";
                                        }
                                    } else {
                                        $sql_trie_order = " ORDER BY 'contact_nom', contact_prenom ";
                                        $sql_trie_order2 = " ORDER BY entretien_dateAppel ";
                                    }
                                    ?>
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_entretien.php">
                                                        <button name="id_contact" value="<?php echo $id_contact ?>" type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un entretien</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                        <input type="hidden" name="entretiens" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="interlocuteur" value="<?php if (isset($_GET['interlocuteur'])) echo $_GET['interlocuteur'] ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="date2" value="<?php if (isset($id_date2)) echo $id_date2 ?>">
                                                        <input type="hidden" name="reponse" value="<?php if (isset($_GET['reponse'])) echo $_GET['reponse'] ?>">
                                                        <input type="hidden" name="rappeler" value="<?php if (isset($_GET['rappeler'])) echo $_GET['rappeler'] ?>">

                                                        <div class="input-group mb-3">
                                                            <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un contact..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                            <datalist id="alternants">
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_id != 0  ORDER BY contact_nom, contact_prenom ") as $unEleve) {
                                                                    $eleve_nom = $unEleve['contact_nom'];
                                                                    $eleve_prenom = $unEleve['contact_prenom'];
                                                                    echo ("<option value='$eleve_nom $eleve_prenom'>");
                                                                }
                                                                ?>
                                                            </datalist>
                                                            <?php if (empty($_GET['search'])) { ?>
                                                                <div class="input-group-prepend">
                                                                    <button type="submit" class="input-group-text cursor" id="search"><?php if (empty($_GET['search'])) echo 'Chercher' ?><?php if (!empty($_GET['search'])) echo '&times;'; ?></button>
                                                                </div>
                                                            <?php } ?>
                                                    </form>

                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                        <input type="hidden" name="entretiens" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="interlocuteur" value="<?php if (isset($_GET['interlocuteur'])) echo $_GET['interlocuteur'] ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="date2" value="<?php if (isset($id_date2)) echo $id_date2 ?>">
                                                        <input type="hidden" name="reponse" value="<?php if (isset($_GET['reponse'])) echo $_GET['reponse'] ?>">
                                                        <input type="hidden" name="rappeler" value="<?php if (isset($_GET['rappeler'])) echo $_GET['rappeler'] ?>">

                                                        <?php if (!empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                            </div>
                                                        <?php } ?>
                                                    </form>
                                                </div>

                                                <div>
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                        <input type="hidden" name="entretiens" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="interlocuteur" value="<?php if (isset($_GET['interlocuteur'])) echo $_GET['interlocuteur'] ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="date2" value="<?php if (isset($id_date2)) echo $id_date2 ?>">
                                                        <input type="hidden" name="reponse" value="<?php if (isset($_GET['reponse'])) echo $_GET['reponse'] ?>">
                                                        <input type="hidden" name="rappeler" value="<?php if (isset($_GET['rappeler'])) echo $_GET['rappeler'] ?>">



                                                        <div class="titre_tableau margin_30">
                                                            <select class="form-control select_etab select_etab_gauche" name="interlocuteur" onchange="this.form.submit()">
                                                                <option value="0">Choisir un interlocuteur</option>
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM membres") as $uneForm) {
                                                                    $id_form = $uneForm['memb_id'];
                                                                    $nom_form = $uneForm['memb_nom'] . " "  . $uneForm['memb_prenom'];
                                                                    if (!empty($_GET['interlocuteur']) and $_GET['interlocuteur'] == $id_form) {
                                                                        echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                    } else {
                                                                        echo ("<option value='$id_form'>$nom_form</option>");;
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                            <input class="form-control select_etab" type="date" name="date" id="date1" value="<?php if (isset($_GET['date']) and $_GET['date'] != 0) echo $_GET['date'] ?>" onchange="this.form.submit()">

                                                            <input class="form-control select_etab" type="date" name="date2" id="date2" value="<?php if (isset($_GET['date2']) and $_GET['date2'] != 0) echo $_GET['date2'] ?>" onchange="this.form.submit()">

                                                            <select class="form-control select_etab" name="reponse" onchange="this.form.submit()">
                                                                <option value="0">Réponse</option>
                                                                <option value="1" <?php if (!empty($_GET['reponse']) and $_GET['reponse'] == 1) echo "selected='selected'" ?>>Oui</option>
                                                                <option value="2" <?php if (!empty($_GET['reponse']) and $_GET['reponse'] == 2) echo "selected='selected'" ?>>Non</option>
                                                            </select>

                                                            <select class="form-control select_etab" name="rappeler" onchange="this.form.submit()">
                                                                <option value="0">Choisir un état</option>
                                                                <option value="1" <?php if (!empty($_GET['rappeler']) and $_GET['rappeler'] == 1) echo "selected='selected'" ?>>À rappeler</option>
                                                                <option value="2" <?php if (!empty($_GET['rappeler']) and $_GET['rappeler'] == 2) echo "selected='selected'" ?>>Rappel manqué</option>
                                                                <option value="3" <?php if (!empty($_GET['rappeler']) and $_GET['rappeler'] == 3) echo "selected='selected'" ?>>Fini</option>
                                                            </select>
                                                        </div>

                                                        <div class="select_label">
                                                            <small class="text-muted select_etab_label">Date d'appel</small>
                                                        </div>
                                                        <div class="select_label2">
                                                            <small class="text-muted select_etab_label">Date de rappel</small>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?php echo $id_contact ?>">

                                                        <input type="hidden" name="entretiens" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="interlocuteur" value="<?php if (isset($id_interlocuteur)) echo $id_interlocuteur ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="date2" value="<?php if (isset($id_date2)) echo $id_date2 ?>">
                                                        <input type="hidden" name="reponse" value="<?php if (isset($_GET['reponse'])) echo $_GET['reponse'] ?>">
                                                        <input type="hidden" name="rappeler" value="<?php if (isset($_GET['rappeler'])) echo $_GET['rappeler'] ?>">

                                                        <?php
                                                        if (isset($_GET['interlocuteur']) and $_GET['interlocuteur'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="interlocuteur" value="0">
                                                                <span aria-hidden="true"><?php echo $get_interlocuteur ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['date']) and $_GET['date'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="date" value="0">
                                                                <span aria-hidden="true"><?php echo $get_date ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['date2']) and $_GET['date2'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="date2" value="0">
                                                                <span aria-hidden="true"><?php echo $get_date2 ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['reponse']) and $_GET['reponse'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="reponse" value="0">
                                                                <span aria-hidden="true"><?php echo $get_reponse ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['rappeler']) and $_GET['rappeler'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="rappeler" value="0">
                                                                <span aria-hidden="true"><?php echo $get_rappeler ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>
                                                    </form>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_entretien_count = requeteSQL($pdo, "SELECT COUNT(*) as total, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id  AND contact_id = $id_contact " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_reponse . $sql_rappeler, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_entretien_count = requeteSQL($pdo, "SELECT COUNT(*) as total, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id  AND contact_id = $id_contact AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_reponse . $sql_rappeler, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_entretien_count) {
                                                ?>
                                                        <tr>
                                                            <th>Contact</th>
                                                            <th>Interlocuteur</th>
                                                            <th>Date d'appel</th>
                                                            <th>Réponse</th>
                                                            <th>Contenu</th>
                                                            <th>État</th>
                                                            <th>Fiche</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_entretien_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_entretien_count entretien $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_entretien_count entretiens $msg_search</h1>";
                                                        }

                                                        $sql_entretien = "SELECT *, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id  AND contact_id = $id_contact " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_date2 . $sql_reponse . $sql_rappeler . $sql_trie_order2 . $ordre2 . ", entretien_dateAppel DESC";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_entretien = "SELECT *, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id  AND contact_id = $id_contact AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_date2 . $sql_reponse . $sql_rappeler . $sql_trie_order2 . $ordre2 . ", entretien_dateAppel DESC";
                                                        }

                                                        $lesEntretiens = lireLesUsers($pdo, $sql_entretien);
                                                        foreach ($lesEntretiens as $unEntretien) {
                                                            $id_ent = $unEntretien['entretien_id'];

                                                            $id_ent_idContact = $unEntretien['entretien_idContact'];
                                                            $nom_contact = requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $id_ent_idContact", "contact_nom") . " " . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $id_ent_idContact", "contact_prenom");

                                                            $id_ent_idMembre = $unEntretien['entretien_idMembre'];
                                                            $nomMembre = requeteSQL($pdo, "SELECT * FROM membres WHERE memb_id = $id_ent_idMembre", "memb_nom") . " " . requeteSQL($pdo, "SELECT * FROM membres WHERE memb_id = $id_ent_idMembre", "memb_prenom");


                                                            $id_ent_contenu = nl2br($unEntretien['entretien_contenu']);
                                                            $id_ent_appel = $unEntretien['entretien_dateAppel'];
                                                            $id_ent_appel_str = dateMySQLToFrLong($id_ent_appel);
                                                            $id_ent_appel_str = $id_ent_appel_str . " <br> " . date('H:i', strtotime("$id_ent_appel"));

                                                            $id_ent_rappel1 = $unEntretien['entretien_dateRappel'];
                                                            $id_ent_rappel2 = $unEntretien['entretien_dateRappel2'];
                                                            $id_ent_rappel_strDate = dateMySQLToFrLong($id_ent_rappel1);

                                                            $reponse = $unEntretien['entretien_reponse'];
                                                            if ($reponse) {
                                                                $reponse = "Oui";
                                                                $class_reponse = "";
                                                            } else {
                                                                $reponse = "Non";
                                                                $class_reponse = "table-danger";
                                                            }

                                                            $etab_id = $unEntretien['etab_id'];
                                                            $etab = $unEntretien['etab_raisonSocial'];
                                                            $next_ent = $unEntretien['entretien_idNextEntretien'];

                                                            if ($dateSQL <= $id_ent_rappel2 and $next_ent == -1) { // à rappeler
                                                                $class_td = "table-primary";
                                                                $id_ent_rappel_str = "Rappeler le " . $id_ent_rappel_strDate . " de " . date('H:i', strtotime("$id_ent_rappel1")) . " à " . date('H:i', strtotime("$id_ent_rappel2"));
                                                                $id_ent_rappel_str = "<a href='ajouter_entretien?id_entretien=$id_ent'>$id_ent_rappel_str</a>";
                                                            } else if ($dateSQL > $id_ent_rappel2 and $next_ent == -1) { //il faut rappeler
                                                                $class_td = "table-danger";
                                                                $id_ent_rappel_str = "Rappel manqué le " . $id_ent_rappel_strDate . " de " . date('H:i', strtotime("$id_ent_rappel1")) . " à " . date('H:i', strtotime("$id_ent_rappel2"));
                                                                $id_ent_rappel_str = "<a href='ajouter_entretien?id_entretien=$id_ent'>$id_ent_rappel_str</a>";
                                                            }

                                                            if ($next_ent != '-1') {
                                                                $id_ent_rappel_str = "Fini";
                                                                $class_td = "table-success";
                                                            }
                                                            if ($next_ent > 0) {
                                                                $id_ent_rappel_str = "<a href='fiche_entretien?id=$next_ent'>Fini, rappelé le " . dateMySQLToFrLong(requeteSQL($pdo, "SELECT * FROM entretiens WHERE entretien_id = $next_ent", "entretien_dateAppel")) . " à " . date('H:i', strtotime(requeteSQL($pdo, "SELECT * FROM entretiens WHERE entretien_id = $next_ent", "entretien_dateAppel"))) . "</a>";
                                                                $class_td = "table-success";
                                                            }

                                                            $id_ent_appel = dateMySQLToFr($id_ent_appel);
                                                            $id_ent_rappel2 = dateMySQLToFr($id_ent_rappel2);

                                                            echo ("<tr>
                                                <td><a href='fiche_contact?id=$id_ent_idContact'>$nom_contact</a></td>
                                                <td><a href='fiche_membre?id=$id_ent_idMembre'>$nomMembre</a></td>
                                                <td><span style='display:none'>$id_ent_appel</span>$id_ent_appel_str</td>
                                                <td class='$class_reponse'>$reponse</td>
                                                <td>$id_ent_contenu</td>
                                                <td class='$class_td'><span style='display:none'>$id_ent_rappel2</span>$id_ent_rappel_str</td>
                                                <td><a href='fiche_entretien?id=$id_ent'>Fiche</a></td></tr>");
                                                        }
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>Aucun entretien $msg_search</h1>";
                                                    }
                                                } catch (Exception $e) {
                                                    die("Ereur grave : " . $e->getMessage());
                                                }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['events'])) echo 'show active' ?>" id="nav-home8" role="tabpanel" aria-labelledby="nav-home-tab8">
                                    <?php

                                    if (isset($_GET['etat']) and $_GET['etat'] != 0) {
                                        $id_etat = $_GET['etat'];

                                        if ($id_etat == 1) {
                                            $get_etat = "À venir";
                                            $sql_etat = " AND '$date' <= event_date";
                                        } else {
                                            $get_etat = "Fini";
                                            $sql_etat = " AND '$date' > event_date";
                                        }
                                    } else {
                                        $id_etat = 0;
                                        $sql_etat = "";
                                    }

                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                        $id_date = $_GET['date'];
                                        $sql_date = " AND event_date = '$id_date'";

                                        $get_date = dateMySQLToFrLong($id_date);;
                                    } else {
                                        $id_date = 0;
                                        $sql_date = "";
                                    }


                                    if (isset($_GET['site']) and $_GET['site'] != 0) {
                                        $id_site = $_GET['site'];
                                        if ($id_site == 1) {
                                            $sql_site = " AND event_idSite = '-1'";
                                            $get_site = "Hébérgé par MFR";
                                        } else {
                                            $sql_site = " AND event_idSite = '0'";
                                            $get_site = "Hébérgé par établissement";
                                        }
                                    } else {
                                        $id_site = 0;
                                        $sql_site = "";
                                    }

                                    if (isset($_GET['type']) and $_GET['type'] != 0) {
                                        $id_type = $_GET['type'];

                                        $sql_type = " AND event_idTypeEvent = $id_type";
                                        $get_type = requeteSQL($pdo, "SELECT * FROM types_evenements WHERE typeEvent_id = $id_type", "typeEvent_intitule");
                                    } else {
                                        $id_type = 0;
                                        $sql_type = "";
                                    }



                                    if (isset($_GET['ordre'])) {
                                        if ($_GET['ordre']) {
                                            $ordre = " DESC";
                                        } else {
                                            $ordre = " ASC";
                                        }
                                    } else {
                                        $ordre = " DESC";
                                    }

                                    if (isset($_GET['trie']) and $_GET['trie'] != 0) {
                                        $get_trie = $_GET['trie'];
                                        if ($get_trie == 1) {
                                            $sql_trie_order = " ORDER BY etab_raisonSocial ";
                                        } else if ($get_trie == 2) {
                                            $sql_trie_order = " ORDER BY typeEvent_intitule ";
                                        }
                                    } else {
                                        $sql_trie_order = " ORDER BY event_date ";
                                    }
                                    ?>

                                    <table class=" table table-striped sortable" cellspacing="0">
                                        <thead>
                                            <div>
                                                <form action="ajouter_evenement.php">
                                                    <input type="hidden" name="id_contact" value="<?php echo $id_contact ?>">
                                                    <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un évènement</button>
                                                </form>
                                            </div>

                                            <div class="margin_bottom20">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">


                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="etat" value="<?php if (isset($id_etat)) echo $id_etat ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="site" value="<?php if (isset($id_site)) echo $id_site ?>">
                                                    <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">

                                                    <div class="input-group mb-3">
                                                        <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un contact ou un établissement..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                        <datalist id="alternants">
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM contacts WHERE contact_id != 0  ORDER BY contact_nom, contact_prenom ") as $unEleve) {
                                                                $eleve_nom = $unEleve['contact_nom'];
                                                                $eleve_prenom = $unEleve['contact_prenom'];
                                                                echo ("<option value='$eleve_nom $eleve_prenom'>");
                                                            }
                                                            ?>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial") as $unEtab) {
                                                                $id_etab2 = $unEtab['etab_id'];
                                                                $raisonsocial = $unEtab['etab_raisonSocial'];
                                                                echo ("<option value='$raisonsocial'>");
                                                            }
                                                            ?>
                                                        </datalist>
                                                        <?php if (empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search"><?php if (empty($_GET['search'])) echo 'Chercher' ?><?php if (!empty($_GET['search'])) echo '&times;'; ?></button>
                                                            </div>
                                                        <?php } ?>
                                                </form>

                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">


                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="etat" value="<?php if (isset($id_etat)) echo $id_etat ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="site" value="<?php if (isset($id_site)) echo $id_site ?>">
                                                    <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">

                                                    <?php if (!empty($_GET['search'])) { ?>
                                                        <div class="input-group-prepend">
                                                            <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                        </div>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                            <div>
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">


                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="etat" value="<?php if (isset($id_etat)) echo $id_etat ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="site" value="<?php if (isset($id_site)) echo $id_site ?>">
                                                    <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">



                                                    <div class="titre_tableau">
                                                        <select class="form-control select_etab select_etab_gauche" name="etat" onchange="this.form.submit()">
                                                            <option value="0">État de l'évènement</option>
                                                            <option value="1" <?php if (!empty($_GET['etat']) and $_GET['etat'] == 1) echo "selected='selected'" ?>>À venir</option>
                                                            <option value="2" <?php if (!empty($_GET['etat']) and $_GET['etat'] == 2) echo "selected='selected'" ?>>Fini</option>
                                                        </select>

                                                        <input class="form-control select_etab" type="date" name="date" value="<?php if (isset($_GET['date']) and $_GET['date'] != 0) echo $_GET['date'] ?>" onchange="this.form.submit()">

                                                        <select class="form-control select_etab" name="site" onchange="this.form.submit()">
                                                            <option value="0">Hébérgé par MFR</option>
                                                            <option value="1" <?php if (!empty($_GET['site']) and $_GET['site'] == 1) echo "selected='selected'" ?>>Oui</option>
                                                            <option value="2" <?php if (!empty($_GET['site']) and $_GET['site'] == 2) echo "selected='selected'" ?>>Non</option>
                                                        </select>

                                                        <select class="form-control select_etab" name="type" onchange="this.form.submit()">
                                                            <option value="0">Choisir un type d'évènement</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM types_evenements") as $unType) {
                                                                $typeEvent_id = $unType['typeEvent_id'];
                                                                $typeEvent_intitule = $unType['typeEvent_intitule'];
                                                                if (isset($_GET['type']) and $_GET['type'] == $typeEvent_id) {
                                                                    echo ("<option value='$typeEvent_id' selected='selected'>$typeEvent_intitule</option>");
                                                                } else {
                                                                    echo ("<option value='$typeEvent_id'>$typeEvent_intitule</option>");
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="titre_tableau">
                                                <form method="GET" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_contact ?>">


                                                    <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                    <input type="hidden" name="etat" value="<?php if (isset($id_etat)) echo $id_etat ?>">
                                                    <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                    <input type="hidden" name="site" value="<?php if (isset($id_site)) echo $id_site ?>">
                                                    <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">

                                                    <?php
                                                    if (isset($_GET['etat']) and $_GET['etat'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="etat" value="0">
                                                            <span aria-hidden="true"><?php echo $get_etat ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['date']) and $_GET['date'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="date" value="0">
                                                            <span aria-hidden="true"><?php echo $get_date ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['site']) and $_GET['site'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="site" value="0">
                                                            <span aria-hidden="true"><?php echo $get_site ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>

                                                    <?php
                                                    if (isset($_GET['type']) and $_GET['type'] != 0) {
                                                    ?>
                                                        <button type="submit" class="close etab_criteres" aria-label="Close" name="type" value="0">
                                                            <span aria-hidden="true"><?php echo $get_type ?> &times;</span>
                                                        </button>
                                                    <?php
                                                    } ?>
                                                </form>
                                            </div>

                                            <?php
                                            try {
                                                $sql_event_count = $pdo->query("SELECT COUNT(*) as total FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND contact_id = $id_contact " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent ")->rowCount();
                                                if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                    $search = htmlentities($_GET['search']);
                                                    $sql_event_count = $pdo->query("SELECT COUNT(*) as total FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND contact_id = $id_contact AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent ")->rowCount();
                                                    $msg_search = " pour '<b>$search</b>'";
                                                } else {
                                                    $msg_search = "";
                                                }
                                                if ($sql_event_count) {
                                            ?>
                                                    <tr>
                                                        <th>Établissement</th>
                                                        <th>Site d'accueil</th>
                                                        <th>Contact(s)</th>
                                                        <th>Type d'évènement</th>
                                                        <th>Description</th>
                                                        <th>Date</th>
                                                        <th>État</th>
                                                        <th>Fiche</th>
                                                    </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                                    if ($sql_event_count == 1) {
                                                        echo "<h1 class='titre_tableau'>$sql_event_count évènement $msg_search</h1>";
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>$sql_event_count évènements $msg_search</h1>";
                                                    }

                                                    $sql_event = "SELECT * FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND contact_id = $id_contact " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent " . $sql_trie_order . $ordre . ", (contact_nom) ASC";
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_event = "SELECT * FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND contact_id = $id_contact  AND (contact_prenom LIKE '%$search%' OR contact_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent " . $sql_trie_order . $ordre . ", (contact_nom) ASC";
                                                    }

                                                    $lesEvents = lireLesUsers($pdo, $sql_event);
                                                    foreach ($lesEvents as $unEvent) {
                                                        $event_id = $unEvent['event_id'];
                                                        $event_idEtab = $unEvent['event_idEtab'];
                                                        $event_idSite = $unEvent['event_idSite'];
                                                        $event_idTypeEvent = $unEvent['event_idTypeEvent'];
                                                        $event_commentaire = nl2br($unEvent['event_commentaire']);
                                                        $event_date = dateMySQLToFrLong($unEvent['event_date']);

                                                        $event_idEtab_nom = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $event_idEtab", "etab_raisonSocial");
                                                        $event_idSite_nom = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $event_idSite", "site_ville");
                                                        if ($event_idSite == -1) {
                                                            $event_idSite_nom = "</a>MFR";
                                                        } else if ($event_idSite == 0) {
                                                            $event_idSite_nom = "</a>Site Principal";
                                                        }
                                                        $event_idTypeEvent_nom = requeteSQL($pdo, "SELECT * FROM types_evenements WHERE typeEvent_id = $event_idTypeEvent", "typeEvent_intitule");

                                                        $list_contact = "";
                                                        $lesContacts = lireLesUsers($pdo, "SELECT * FROM participation_event WHERE participation_idEvent = $event_id AND participation_idContact <> '0'");
                                                        foreach ($lesContacts as $unContact) {
                                                            $event_idContact = $unContact['participation_idContact'];
                                                            $event_lesContact = "<a href='fiche_contact?id=$event_idContact'>" . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $event_idContact", "contact_nom") . " " . requeteSQL($pdo, "SELECT * FROM contacts WHERE contact_id = $event_idContact", "contact_prenom") . "</a>";
                                                            if (empty($list_contact)) {
                                                                $list_contact = $event_lesContact;
                                                            } else {
                                                                $list_contact = $list_contact . "<br>" . $event_lesContact;
                                                            }
                                                        }

                                                        if (empty($list_contact)) {
                                                            $list_contact = "Pas précisé";
                                                        }

                                                        if ($unEvent['event_date'] >= $date) {
                                                            $class_td = "table-primary";
                                                            $etat = "A venir";
                                                        } else {
                                                            $class_td = "table-success";
                                                            $etat = "Fini";
                                                        }

                                                        echo ("<tr><td><a href='fiche_etablissement?id=$event_idEtab'>$event_idEtab_nom</a></td>
                                                <td><a href='fiche_site?id=$event_idSite'>$event_idSite_nom</a></td>
                                                <td>$list_contact</td>
                                                <td>$event_idTypeEvent_nom</td>
                                                <td>$event_commentaire</td>
                                                <td>$event_date</td>
                                                <td class=$class_td>$etat</td>
                                                <td><a href='fiche_evenement?id=$event_id'>Fiche</a></td></tr>");
                                                    }
                                                } else {
                                                    echo "<h1 class='titre_tableau'>Aucun évènement $msg_search</h1>";
                                                }
                                            } catch (Exception $e) {
                                                die("Ereur grave : " . $e->getMessage());
                                            }
                                    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer ce contact</button>

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
                                            Êtes-vous sûr de vouloir supprimer ce contact et toutes les données qui lui sont liées ?<br><br>
                                            Cette action est irréversible
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                            <form action="delete.php" method="GET">
                                                <button name="id_contact" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                </div>
            </section>
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