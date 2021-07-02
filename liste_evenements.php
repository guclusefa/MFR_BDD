<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    if (isset($_SESSION['id']) AND $id_admin > 0) {
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
                $get_site = "Hébergé par MFR";
            } else {
                $sql_site = " AND event_idSite = '0'";
                $get_site = "Hébergé par établissement";
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

        <head>
            <title>MFR BDD - Les évènements</title>
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
                            <h1>Liste des évènements</h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Les évènements</a>
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
                                                    <form action="ajouter_evenement.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un évènement</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
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
                                                                <option value="0">Hébergé par MFR</option>
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
                                                    $sql_event_count = $pdo->query("SELECT COUNT(*) as total FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent ")->rowCount();
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_event_count = $pdo->query("SELECT COUNT(*) as total FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND (etab_raisonSocial LIKE '%$search%' OR contact_nom LIKE '%$search%' OR contact_prenom LIKE '%$search%') " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent ")->rowCount();
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

                                                        $sql_event = "SELECT * FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent " . $sql_trie_order . $ordre . ", (contact_nom) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = htmlentities($_GET['search']);
                                                            $sql_event = "SELECT * FROM evenements, etablissements, sites_entreprise, types_evenements, contacts, participation_event WHERE participation_idEvent = event_id AND participation_idContact = contact_id AND event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND (contact_prenom LIKE '%$search%' OR contact_nom LIKE '%$search%' OR etab_raisonSocial LIKE '%$search%') " . $sql_etat . $sql_date . $sql_site . $sql_type . " GROUP BY participation_idEvent " . $sql_trie_order . $ordre . ", (contact_nom) ASC LIMIT $limit";
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