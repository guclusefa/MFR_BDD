<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    if (isset($_SESSION['id']) AND $id_admin > 0) {
?>

        <head>
            <title>MFR BDD - Emailing</title>
        </head>

        <body>
            <section id="tabs" class="project-tab">
                <div class="container-fluid">
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
                    <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-10">
                            <h1>Emailing</h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Les contacts</a>
                                    <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home2" role="tab" aria-controls="nav-home2" aria-selected="true">Les alternances</a>
                                </div>
                            </nav>

                            <div>
                                <?php
                                if (isset($_GET['limit'])) {
                                    $limit = $_GET['limit'];
                                } else {
                                    $limit = 50;
                                }
                                ?>
                                <form method="GET">
                                    <?php
                                    foreach ($_GET as $name => $value) {
                                        $name = htmlspecialchars($name);
                                        $value = htmlspecialchars($value);
                                        echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                                    }
                                    ?>
                                    <label for="limit" class="form-label text-muted">Afficher</label>
                                    <select class="form-control" name="limit" id="limit" onchange="this.form.submit()">
                                        <option <?php if (isset($limit) and $limit == 10) echo "selected='selected'" ?>>10</option>
                                        <option <?php if (isset($limit) and $limit == 25) echo "selected='selected'" ?>>25</option>
                                        <option <?php if (isset($limit) and $limit == 50) echo "selected='selected'" ?>>50</option>
                                        <option <?php if (isset($limit) and $limit == 100) echo "selected='selected'" ?>>100</option>
                                        <option value="9999" <?php if (isset($limit) and $limit == 9999) echo "selected='selected'" ?>>Tout</option>
                                    </select>
                                </form>
                            </div>

                            <div class="tab-content" id="nav-tabContent">
                                <div class="text-nowrap tab-pane fade <?php if (!isset($_GET['alternances'])) echo 'show active' ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
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
                                        } else {
                                            $sql_trie_order = " ORDER BY 'contact_nom', contact_prenom ";
                                            $sql_trie_order2 = " ORDER BY entretien_dateAppel ";
                                        }
                                    } else {
                                        $sql_trie_order = " ORDER BY 'contact_nom', contact_prenom ";
                                        $sql_trie_order2 = " ORDER BY entretien_dateAppel ";
                                    }
                                    ?>
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div class="margin_bottom20 margin_30">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="fonction" value="<?php if (isset($id_fonction)) echo $id_fonction ?>">
                                                        <input type="hidden" name="appel" value="<?php if (isset($_GET['appel'])) echo $_GET['appel'] ?>">

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
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="fonction" value="<?php if (isset($id_fonction)) echo $id_fonction ?>">
                                                        <input type="hidden" name="appel" value="<?php if (isset($_GET['appel'])) echo $_GET['appel'] ?>">

                                                        <?php if (!empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                            </div>
                                                        <?php } ?>
                                                    </form>
                                                </div>
                                                <div>
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="fonction" value="<?php if (isset($id_fonction)) echo $id_fonction ?>">
                                                        <input type="hidden" name="appel" value="<?php if (isset($_GET['appel'])) echo $_GET['appel'] ?>">

                                                        <div class="titre_tableau">
                                                            <select class="form-control select_etab select_etab_gauche" name="fonction" onchange="this.form.submit()">
                                                                <option value="0">Choisir une fonction</option>
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM fonctions_contacts") as $uneForm) {
                                                                    $id_form = $uneForm['fonction_id'];
                                                                    $nom_form = $uneForm['fonction_intitule'];
                                                                    if (!empty($_GET['fonction']) and $_GET['fonction'] == $id_form) {
                                                                        echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                    } else {
                                                                        echo ("<option value='$id_form'>$nom_form</option>");;
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                            <select class="form-control select_etab" name="appel" onchange="this.form.submit()">
                                                                <option value="0">Intéressé</option>
                                                                <option value="1" <?php if (!empty($_GET['appel']) and $_GET['appel'] == 1) echo "selected='selected'" ?>>Oui</option>
                                                                <option value="2" <?php if (!empty($_GET['appel']) and $_GET['appel'] == 2) echo "selected='selected'" ?>>Non</option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="fonction" value="<?php if (isset($id_fonction)) echo $id_fonction ?>">
                                                        <input type="hidden" name="appel" value="<?php if (isset($_GET['appel'])) echo $_GET['appel'] ?>">

                                                        <?php
                                                        if (isset($_GET['fonction']) and $_GET['fonction'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="fonction" value="0">
                                                                <span aria-hidden="true"><?php echo $get_fonction ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['appel']) and $_GET['appel'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="appel" value="0">
                                                                <span aria-hidden="true"><?php echo $get_appel ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau" style="font-size:20px;display:block;">
                                                    <script language="JavaScript">
                                                        // quand on toggle les case
                                                        function toggle(source, origine) {
                                                            checkboxes = document.getElementsByName(origine);
                                                            for (var i = 0, n = checkboxes.length; i < n; i++) {
                                                                checkboxes[i].checked = source.checked;
                                                            }
                                                        }

                                                        // stock tout les mails
                                                        function lesEmails(origine) {
                                                            var lesmails_str = ""
                                                            var lesemails = []
                                                            var checkboxes = document.querySelectorAll('input[name=' + origine + ']:checked')
                                                            for (var i = 0; i < checkboxes.length; i++) {
                                                                lesemails.push(checkboxes[i].value)
                                                            }
                                                            for (var i = 0; i < lesemails.length; i++) {
                                                                if (lesemails[i].length > 0) {
                                                                    lesmails_str = lesmails_str + "" + lesemails[i] + ","
                                                                }
                                                            }
                                                            if (!lesmails_str.replace(/\s/g, '').length) {
                                                                alert("Choisir au moins un contact ou un alternant !")
                                                            } else {
                                                                window.location.href = "mailto:<?php echo $requete['memb_mail'] ?>,?bcc=" + lesmails_str;
                                                            }
                                                        }

                                                        //affichage nmbre de toggled
                                                        function getNbChecked() {
                                                            document.getElementById("email_contact").innerHTML = document.querySelectorAll('input[name="emailcontact"]:checked').length
                                                            document.getElementById("email_alt").innerHTML = document.querySelectorAll('input[name="emailalt"]:checked').length
                                                        }
                                                    </script>
                                                    <input type="checkbox" id="toutcontact" name="toutcontact" onClick="toggle(this, 'emailcontact'), getNbChecked()" />
                                                    <label for='toutcontact'>Sélectionner tout les contacts</label><br>

                                                    <h1 class="text-muted"><b><span id="email_contact">0</span></b> contact(s) sélectionné(s)</h1>
                                                    <button onclick="lesEmails('emailcontact')" class="btn btn-primary button_w100 margin_30">Valider</button>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_contact_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM contacts WHERE contact_id != 0 " . $sql_fonction . $sql_appel, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_contact_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM contacts, etablissements, fonctions_contacts WHERE contact_id != 0 AND contact_idEtab = etab_id  AND contact_idFonction = fonction_id AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_fonction . $sql_appel, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_contact_count) {
                                                ?>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Établissement</th>
                                                            <th>Site</th>
                                                            <th>Fonction</th>
                                                            <th>Tél</th>
                                                            <th>Mail</th>
                                                            <th>Intéressé</th>
                                                            <th>Sélectionner</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_contact_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_contact_count contact $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_contact_count contacts $msg_search</h1>";
                                                        }

                                                        $sql_contact = "SELECT * FROM contacts, etablissements, fonctions_contacts WHERE contact_id != 0 AND contact_idEtab = etab_id  AND contact_idFonction = fonction_id " . $sql_fonction . $sql_appel . $sql_trie_order . $ordre . ", 'contact_nom', 'contact_prenom' ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_contact = "SELECT * FROM contacts, etablissements, fonctions_contacts WHERE contact_id != 0 AND contact_idEtab = etab_id  AND contact_idFonction = fonction_id AND (contact_prenom LIKE '%$search%' OR contact_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_fonction . $sql_appel . $sql_trie_order . $ordre . ", 'contact_nom', 'contact_prenom' ASC LIMIT $limit";
                                                        }

                                                        $i = 1;
                                                        $lesContacts = lireLesUsers($pdo, $sql_contact);
                                                        foreach ($lesContacts as $unContact) {
                                                            $contact_id = $unContact['contact_id'];
                                                            $contact_idEtab = $unContact['contact_idEtab'];
                                                            $contact_idEtab_raisonsocial = $unContact['etab_raisonSocial'];
                                                            $contact_idSite = $unContact['contact_idSite'];
                                                            if (!$contact_idSite) {
                                                                $contact_idSite_intitule = "</a>Site Principal";
                                                            } else {
                                                                $contact_idSite_intitule = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_id = $contact_idSite", "typeSite_intitule");
                                                            }
                                                            $contact_idFonction = $unContact['contact_idFonction'];
                                                            $contact_idFonction_intitule = requeteSQL($pdo, "SELECT * FROM fonctions_contacts WHERE fonction_id = $contact_idFonction", "fonction_intitule");
                                                            $contact_nom = $unContact['contact_nom'];
                                                            $contact_prenom = $unContact['contact_prenom'];
                                                            $contact_tel = $unContact['contact_tel'];
                                                            $contact_mail = $unContact['contact_mail'];
                                                            $contact_nbrEntretien = requeteSQL($pdo, "SELECT COUNT(*) as contact_nbrEntretien FROM entretiens WHERE entretien_idContact = $contact_id", "contact_nbrEntretien");
                                                            $contact_actif = $unContact['contact_actif'];
                                                            if ($contact_actif) {
                                                                $contact_actif = "Oui";
                                                                $class_td = "";
                                                            } else {
                                                                $contact_actif = "Non";
                                                                $class_td = "table-danger";
                                                            }

                                                            echo ("<tr><td><a href='fiche_contact?id=$contact_id'>$contact_nom   $contact_prenom</td> 
                                                <td><a href='fiche_etablissement?id=$contact_idEtab'>$contact_idEtab_raisonsocial</a></td>
                                                <td><a href='fiche_site?id=$contact_idSite'>$contact_idSite_intitule</a></td>
                                                <td>$contact_idFonction_intitule</td>
                                                <td>$contact_tel</td>
                                                <td><a href='mailto:$contact_mail'>$contact_mail</a></td>
                                                <td class=$class_td>$contact_actif</td>
                                                <td>
                                                <input onclick='getNbChecked()' class='form-check-control' type='checkbox' id='emailcontact$i' name='emailcontact' value=$contact_mail>
                                                <label class='form-label' for='emailcontact$i'>Sélectionner</label></td></tr>");
                                                            $i++;
                                                        }
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>Aucun contact $msg_search</h1>";
                                                    }
                                                } catch (Exception $e) {
                                                    die("Ereur grave : " . $e->getMessage());
                                                }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class=" text-nowrap tab-pane fade <?php if (isset($_GET['alternances'])) echo 'show active' ?>" id="nav-home2" role="tabpanel" aria-labelledby="nav-home-tab2">
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
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>


                                                <div class="margin_bottom20 margin_30">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="alternances" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="formation" value="<?php if (isset($id_formation)) echo $id_formation ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="situation" value="<?php if (isset($id_situation)) echo $id_situation ?>">

                                                        <div class="input-group mb-3">
                                                            <input list="alternants" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un(e) alternant(e) ou un établissement..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                            <datalist id="alternants">
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM eleves, offres_alternances GROUP BY eleve_nom, eleve_prenom  ORDER BY eleve_nom, eleve_prenom ") as $unEleve) {
                                                                    $eleve_nom = $unEleve['eleve_nom'];
                                                                    $eleve_prenom = $unEleve['eleve_prenom'];
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
                                                        <input type="hidden" name="alternances" value="1">
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
                                                        <input type="hidden" name="alternances" value="1">
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
                                                        <input type="hidden" name="alternances" value="1">
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

                                                <div class="titre_tableau" style="font-size:20px;display:block;">
                                                    <input type="checkbox" id="toutalt" name="toutalt" onClick="toggle(this, 'emailalt'), getNbChecked()" />
                                                    <label for='toutalt'>Sélectionner tout les alternants</label>

                                                    <h1 class="text-muted"><b><span id="email_alt">0</span></b> alternant(s) sélectionné(s)</h1>
                                                    <button onclick="lesEmails('emailalt')" class="btn btn-primary button_w100 margin_30">Valider</button>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_alt_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_id = alt_id " . $sql_formation . $sql_date . $sql_situation, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_alt_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances, etablissements, eleves WHERE etab_id = alt_idEtab  AND eleve_id = alt_idEleve AND (etab_raisonSocial LIKE '%$search%' OR eleve_nom LIKE '%$search%' OR eleve_prenom LIKE '%$search%') " . $sql_formation . $sql_date . $sql_situation, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_alt_count) {
                                                ?>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Tuteur</th>
                                                            <th>Établissement</th>
                                                            <th>Site</th>
                                                            <th>Formation</th>
                                                            <th>Début</th>
                                                            <th>Fin</th>
                                                            <th>Situation</th>
                                                            <th>Mail</th>
                                                            <th>Sélectionner</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_alt_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_alt_count alternant(e) $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_alt_count alternant(e)s $msg_search</h1>";
                                                        }
                                                        $sql_alt = "SELECT *  FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idEleve = eleve_id AND alt_idFormation = formation_id AND etab_id = alt_idEtab " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_alt = "SELECT * FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idFormation = formation_id AND alt_idEleve = eleve_id AND etab_id = alt_idEtab AND (eleve_prenom LIKE '%$search%' OR eleve_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC LIMIT $limit";
                                                        }
                                                        $i = 1;
                                                        $lesEleves = lireLesUsers($pdo, $sql_alt);
                                                        foreach ($lesEleves as $unEleve) {
                                                            $id_alt = $unEleve['alt_id'];
                                                            $id_etab = $unEleve['alt_idEtab'];
                                                            $id_etab_nom = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $id_etab", "etab_raisonSocial");
                                                            $id_eleve = $unEleve['eleve_id'];
                                                            $nom_eleve = $unEleve['eleve_nom'] . " " . $unEleve['eleve_prenom'];
                                                            $nom_formation = $unEleve['formation_niv'];
                                                            $anneeFor = $unEleve['alt_anneeFormation'];
                                                            $dateDebut = $unEleve['alt_debut'];
                                                            $dateFin = $unEleve['alt_fin'];
                                                            $site_id = $unEleve['alt_idSite'];
                                                            $alt_contact_id = $unEleve['alt_idContact'];
                                                            $alt_actif = $unEleve['alt_actif'];
                                                            $mail_alt = $unEleve['eleve_mailperso'];

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
                                                                $annee1 = date('Y-m-d', strtotime('+1 year', strtotime($dateDebut)));
                                                                $annee2 = date('Y-m-d', strtotime('+2 years', strtotime($dateDebut)));
                                                                $annee3 = date('Y-m-d', strtotime('+3 years', strtotime($dateDebut)));
                                                                if ($date <= $annee1) {
                                                                    $situation = "1ère année";
                                                                    $class_td = "";
                                                                } else if ($date <= $annee2) {
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
                                                <td><a href='fiche_etablissement?id=$id_etab'>$id_etab_nom</a></td>
                                                <td><a href='fiche_site?id=$site_id'>$site_nom</a></td>
                                                <td>$nom_formation</td>
                                                <td>$dateDebut</td>
                                                <td>$dateFin</td>
                                                <td class=$class_td>$situation</td>
                                                <td><a href='mailto:$mail_alt'>$mail_alt</a></td>
                                                <td><input onclick='getNbChecked()' class='form-check-control' type='checkbox' id='emailalt$i' name='emailalt' value=$mail_alt>
                                                <label class='form-label' for='emailalt$i'>Sélectionner</label></td></tr>");
                                                            $i++;
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