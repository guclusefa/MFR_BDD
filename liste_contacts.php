<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    if (isset($_SESSION['id']) AND $id_admin > 0) {
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

        <head>
            <title>MFR BDD - Les contacts</title>
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
                            <h1>Liste des contacts & des entretiens</h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link <?php if (!isset($_GET['entretiens'])) echo 'active' ?>" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Les contacts</a>
                                    <a class="nav-item nav-link <?php if (isset($_GET['entretiens'])) echo 'active' ?>" id="nav-home-tab2" data-toggle="tab" href="#nav-home2" role="tab" aria-controls="nav-home2" aria-selected="true">Les entretiens</a>
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
                                <div class="text-nowrap tab-pane fade <?php if (!isset($_GET['entretiens'])) echo 'show active' ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_contact.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un contact</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
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
                                                            <th>Entretien</th>
                                                            <th>Fiche</th>
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
                                                <td><a href='ajouter_entretien?id_etab=$contact_idEtab&id_contact=$contact_id'>Ajouter</a></td>
                                                <td><a href='fiche_contact?id=$contact_id'>Fiche</a></td></tr>");
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

                                <div class="text-nowrap tab-pane fade <?php if (isset($_GET['entretiens'])) echo 'show active' ?>" id="nav-home2" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_entretien.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un entretien</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="entretiens" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>">
                                                        <input type="hidden" name="interlocuteur" value="<?php if (isset($_GET['interlocuteur'])) echo $_GET['interlocuteur'] ?>">
                                                        <input type="hidden" name="date" value="<?php if (isset($id_date)) echo $id_date ?>">
                                                        <input type="hidden" name="date2" value="<?php if (isset($id_date2)) echo $id_date2 ?>">
                                                        <input type="hidden" name="reponse" value="<?php if (isset($_GET['reponse'])) echo $_GET['reponse'] ?>">
                                                        <input type="hidden" name="rappeler" value="<?php if (isset($_GET['rappeler'])) echo $_GET['rappeler'] ?>">

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
                                                    $sql_entretien_count = requeteSQL($pdo, "SELECT COUNT(*) as total, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id" . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_reponse . $sql_rappeler, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_entretien_count = requeteSQL($pdo, "SELECT COUNT(*) as total, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_reponse . $sql_rappeler, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_entretien_count) {
                                                ?>
                                                        <tr>
                                                            <th>Établissement</th>
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

                                                        $sql_entretien = "SELECT *, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id" . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_date2 . $sql_reponse . $sql_rappeler . $sql_trie_order2 . $ordre2 . ", entretien_dateAppel DESC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_entretien = "SELECT *, CAST(entretien_dateRappel as date) FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_interlocuteur . $sql_date . $sql_date2 . $sql_date2 . $sql_reponse . $sql_rappeler . $sql_trie_order2 . $ordre2 . ", entretien_dateAppel DESC LIMIT $limit";
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

                                                            echo ("<tr><td><a href='fiche_etablissement?id=$etab_id'>$etab</a></td>
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