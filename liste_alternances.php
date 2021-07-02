<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    if (isset($_SESSION['id']) and $id_admin > 0) {
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

        //trier par
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

        <head>
            <title>MFR BDD - Les alternances</title>
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
                            <h1>Liste des alternances, offres & des demandes</h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link <?php if (!isset($_GET['offres']) and !isset($_GET['demandes'])) echo 'active' ?>" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Les alternances</a>
                                    <a class="nav-item nav-link <?php if (isset($_GET['offres'])) echo 'active' ?>" id="nav-home-tab2" data-toggle="tab" href="#nav-home2" role="tab" aria-controls="nav-home2" aria-selected="true">Les offres</a>
                                    <a class="nav-item nav-link <?php if (isset($_GET['demandes'])) echo 'active' ?>" id="nav-home-tab3" data-toggle="tab" href="#nav-home3" role="tab" aria-controls="nav-home3" aria-selected="true">Les demandes</a>
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
                                <div class=" text-nowrap tab-pane fade <?php if (!isset($_GET['offres']) and !isset($_GET['demandes'])) echo 'show active' ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div class="">
                                        <table class="  table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_alternance.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un(e) alternant(e)</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
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
                                                        $sql_alt = "SELECT *  FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idEleve = eleve_id AND alt_idFormation = formation_id AND etab_id = alt_idEtab " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_alt = "SELECT * FROM alternances, eleves, etablissements, formations, contacts WHERE contact_id = alt_idContact AND alt_idFormation = formation_id AND alt_idEleve = eleve_id AND etab_id = alt_idEtab AND (eleve_prenom LIKE '%$search%' OR eleve_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation . $sql_date . $sql_situation . $sql_trie_order . $ordre . ", (eleve_nom) ASC LIMIT $limit";
                                                        }
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
                                                <td><a href='fiche_etablissement?id=$id_etab'>$id_etab_nom</a></td>
                                                <td><a href='fiche_site?id=$site_id'>$site_nom</a></td>
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
                                </div>

                                <div class=" text-nowrap tab-pane fade <?php if (isset($_GET['offres'])) echo 'show active' ?>" id="nav-home2" role="tabpanel" aria-labelledby="nav-home-tab2">
                                    <div class="">
                                        <table class="  table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_offre">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter une offre</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="offres" value="1">
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
                                                    $sql_offre_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_offre_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_offre_count) {
                                                ?>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Tuteur</th>
                                                            <th>Établissement</th>
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
                                                        if ($sql_offre_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_offre_count offre d'alternance $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_offre_count offres d'alternances $msg_search</h1>";
                                                        }
                                                        $sql_offre = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_offre = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 0 AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC LIMIT $limit";
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
                                                <td><a href='fiche_etablissement?id=$id_offre_etab'>$id_offre_etab_rs</a></td>
                                                <td><a href='fiche_site?id=$site_id'>$site_nom</a></td>
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
                                </div>

                                <div class=" text-nowrap tab-pane fade <?php if (isset($_GET['demandes'])) echo 'show active' ?>" id="nav-home3" role="tabpanel" aria-labelledby="nav-home-tab3">
                                    <div class="">
                                        <table class=" table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form method="GET" action="ajouter_offre">
                                                        <button type="submit" name="id_demande" value="1" class="btn btn-primary button_w100 margin_30">Ajouter une demande</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="demandes" value="1">
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
                                                    $sql_demande_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1" . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_demande_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_demande_count) {
                                                ?>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>Tuteur</th>
                                                            <th>Établissement</th>
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
                                                        $sql_demande = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_demande = "SELECT * FROM offres_alternances, etablissements, formations, contacts WHERE contact_id = offreAlt_idContact AND offreAlt_idEtab = etab_id AND offreAlt_idFormation = formation_id AND offreAlt_demande = 1 AND (offreAlt_prenom LIKE '%$search%' OR offreAlt_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_formation2 . $sql_date2 . $sql_situation2 . $sql_trie_order2 . $ordre . ", (offreAlt_nom) ASC LIMIT $limit";
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
                                                <td><a href='fiche_etablissement?id=$id_offre_etab'>$id_offre_etab_rs</a></td>
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