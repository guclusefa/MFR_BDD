<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    if (isset($_SESSION['id']) AND $id_admin > 0) {
        if (isset($_GET['departement']) and $_GET['departement'] != 0) { // si critre choisie
            $id_departement = $_GET['departement'];
            $sql_departement = " AND etab_idDepartement = '$id_departement'"; // pour etab
            $sql_departement2 = " AND site_idDepartement = '$id_departement'"; // pour site
            $get_departement = requeteSQL($pdo, "SELECT * FROM departement WHERE departement_id = '$id_departement'", "departement_code");
            $get_departement = $get_departement . " - " . requeteSQL($pdo, "SELECT * FROM departement WHERE departement_id = '$id_departement'", "departement_nom");
        } else { // par défaut
            $id_departement = 0;
            $sql_departement = "";
            $sql_departement2 = "";
        }
        if (isset($_GET['ville']) and $_GET['ville'] != '0') {
            $id_ville = $_GET['ville'];
            $sql_ville = " AND etab_ville = '$id_ville'";
            $sql_ville2 = " AND site_ville = '$id_ville'";
        } else {
            $id_ville = 0;
            $sql_ville = "";
            $sql_ville2 = "";
        }
        if (isset($_GET['secteur']) and $_GET['secteur'] != 0) {
            $id_secteur = $_GET['secteur'];
            $sql_secteur = " AND etab_idSecteur = '$id_secteur'";
            $id_secteur_nom = requeteSQL($pdo, "SELECT * FROM secteurs_entreprise WHERE secteur_id = $id_secteur", "secteur_nom");
        } else {
            $id_secteur = 0;
            $sql_secteur = "";
        }
        if (isset($_GET['type']) and $_GET['type'] != 0) {
            $id_type = $_GET['type'];
            $sql_type = " AND etab_idType = '$id_type'";
            $id_type_nom = requeteSQL($pdo, "SELECT * FROM types_entreprise WHERE typeEnt_id = $id_type", "typeEnt_intitule");
        } else {
            $id_type = 0;
            $sql_type = "";
        }
        if (isset($_GET['origine']) and $_GET['origine'] != 0) {
            $id_origine = $_GET['origine'];
            $id_origine_nom = requeteSQL($pdo, "SELECT * FROM origines_entreprise WHERE origineEnt_id = $id_origine", "origineEnt_intitule");
            if ($id_origine == 1) {
                $sql_origine = " AND etab_donnateur = '$id_origine'";
                $id_origine_nom = "Donateur";
            } else {
                $sql_origine = " AND etab_idOrigine = '$id_origine'";
            }
        } else {
            $id_origine = 0;
            $sql_origine = "";
        }

        if (isset($_GET['type_site']) and $_GET['type_site'] != 0) {
            $id_type_site = $_GET['type_site'];
            $sql_type_site = " AND site_idTypeSite = '$id_type_site'";
            $id_type_site_nom = requeteSQL($pdo, "SELECT * FROM types_sites WHERE typeSite_id = $id_type_site", "typeSite_intitule");
        } else {
            $id_type_site = 0;
            $sql_type_site = "";
        }

        if (isset($_GET['interesse']) and $_GET['interesse'] != 0) {
            $id_interesse = $_GET['interesse'];
            if ($id_interesse == 1) {
                $get_interesse = "Oui";
            }
            if ($id_interesse == 2) {
                $id_interesse = 0;
                $get_interesse = "Non";
            }
            $sql_interesse = " AND etab_interest = $id_interesse ";
        } else {
            $id_interesse = 0;
            $sql_interesse = "";
        }


        $ordre = " ASC"; // par défaut

        $sql_trie_select = "etab_id";
        $sql_trie_order = "ORDER BY etab_raisonSocial ";

        $sql_trie_select2 = "site_id";
        $sql_trie_order2 = "ORDER BY etab_raisonSocial ";
?>

        <head>
            <title>MFR BDD - Les établissements</title>
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
                            <h1>Liste des établissements & de leurs sites secondaires</h1>
                            <br>
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link <?php if (empty($_GET['sites']) and empty($_GET['collabes'])) echo 'active' ?>" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Les établissements</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['sites'])) echo 'active' ?>" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Les sites secondaires</a>
                                    <a class="nav-item nav-link <?php if (!empty($_GET['collabes'])) echo 'active' ?>" id="nav-profile-tab2" data-toggle="tab" href="#nav-profile2" role="tab" aria-controls="nav-profile2" aria-selected="false">Les collaborations CFA</a>
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
                                <div class="text-nowrap tab-pane fade <?php if (empty($_GET['sites']) and empty($_GET['collabes'])) echo 'show active' ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <div class="">
                                        <table class=" table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_etablissement.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un établissement</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="secteur" value="<?php if (isset($id_secteur)) echo $id_secteur ?>">
                                                        <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">
                                                        <input type="hidden" name="origine" value="<?php if (isset($id_origine)) echo $id_origine ?>">

                                                        <div class="input-group mb-3 active">
                                                            <input list="etablissements" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un établissement..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                            <datalist id="etablissements">
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
                                                        <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="secteur" value="<?php if (isset($id_secteur)) echo $id_secteur ?>">
                                                        <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">
                                                        <input type="hidden" name="origine" value="<?php if (isset($id_origine)) echo $id_origine ?>">
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
                                                        <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="secteur" value="<?php if (isset($id_secteur)) echo $id_secteur ?>">
                                                        <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">
                                                        <input type="hidden" name="origine" value="<?php if (isset($id_origine)) echo $id_origine ?>">

                                                        <div class="titre_tableau">
                                                            <select class="form-control select_etab select_etab_gauche" name="departement" onchange="this.form.submit()">
                                                                <option value="0">Choisir un département</option>
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM departement") as $unDepart) {
                                                                    $iddepart = $unDepart['departement_id'];
                                                                    $codeDepart = $unDepart['departement_code'];
                                                                    $nomDepart = $unDepart['departement_nom'];
                                                                    if (isset($_GET['departement']) and $iddepart == $_GET['departement']) {
                                                                        echo ("<option value='$iddepart' selected='selected'>$codeDepart - $nomDepart</option>");
                                                                    } else {
                                                                        echo ("<option value='$iddepart'>$codeDepart - $nomDepart</option>");
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <select class="form-control select_etab" name="ville" onchange="this.form.submit()">
                                                                <option value="0">Choisir une ville</option>
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM etablissements WHERE etab_ville <> ''  GROUP BY etab_ville ORDER BY etab_ville") as $unDepart) {
                                                                    $etab_ville = $unDepart['etab_ville'];
                                                                    if (isset($_GET['ville']) and $etab_ville == $_GET['ville']) {
                                                                        echo ("<option selected='selected'>$etab_ville</option>");
                                                                    } else {
                                                                        echo ("<option>$etab_ville</option>");
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                            <select class="form-control select_etab" name="secteur" onchange="this.form.submit()">
                                                                <option value="0">Choisir un secteur</th>
                                                                    <?php
                                                                    foreach (lireLesUsers($pdo, "SELECT * FROM secteurs_entreprise ORDER BY secteur_nom") as $unDepart) {
                                                                        $idsecteur = $unDepart['secteur_id'];
                                                                        $nomsecteur = $unDepart['secteur_nom'];
                                                                        if (isset($_GET['secteur']) and $idsecteur == $_GET['secteur']) {
                                                                            echo ("<option value='$idsecteur' selected='selected'>$nomsecteur</option>");
                                                                        } else {
                                                                            echo ("<option value='$idsecteur'>$nomsecteur</option>");
                                                                        }
                                                                    }
                                                                    ?>
                                                            </select>

                                                            <select class="form-control select_etab" name="type" onchange="this.form.submit()">
                                                                <option value="0">Choisir un type</th>
                                                                    <?php
                                                                    foreach (lireLesUsers($pdo, "SELECT * FROM types_entreprise") as $unType) {
                                                                        $idtype = $unType['typeEnt_id'];
                                                                        $nomtype = $unType['typeEnt_intitule'];
                                                                        $nbrsalaries = $unType['typeEnt_nbrSalaries'];
                                                                        if (isset($_GET['type']) and $_GET['type'] == $idtype and $idtype != 5) {
                                                                            echo ("<option value='$idtype' selected='selected'>$nomtype ($nbrsalaries salariés)</option>");
                                                                        } else if (isset($_GET['type']) and $_GET['type'] ==  $idtype and $idtype == 5) {
                                                                            echo ("<option value='$idtype' selected='selected'>$nomtype</option>");
                                                                        } else if (isset($_GET['type']) and $_GET['type'] !=  $idtype and $idtype == 5) {
                                                                            echo ("<option value='$idtype'>$nomtype</option>");
                                                                        } else if (!isset($_GET['type']) and $idtype == 5) {
                                                                            echo ("<option value='$idtype'>$nomtype</option>");
                                                                        } else {
                                                                            echo ("<option value='$idtype'>$nomtype ($nbrsalaries salariés)</option>");
                                                                        }
                                                                    }
                                                                    ?>
                                                            </select>
                                                            <select class="form-control select_etab" name="origine" onchange="this.form.submit()">
                                                                <option value="0">Choisir une origine</th>
                                                                <option value='1' <?php if (isset($_GET['origine']) and $_GET['origine'] == 1) echo "selected='selected'" ?>>Donateur</option>
                                                                <?php
                                                                foreach (lireLesUsers($pdo, "SELECT * FROM origines_entreprise") as $uneOrigine) {
                                                                    $idorigine = $uneOrigine['origineEnt_id'];
                                                                    $nomorigine = $uneOrigine['origineEnt_intitule'];
                                                                    if (isset($_GET['origine']) and $idorigine == $_GET['origine']) {
                                                                        echo ("<option value='$idorigine' selected='selected'>$nomorigine</option>");
                                                                    } else {
                                                                        echo ("<option value='$idorigine'>$nomorigine</option>");
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                            <select class="form-control select_etab select_etab_gauche" name="interesse" onchange="this.form.submit()">
                                                                <option value="0">Intéressé par offres</option>
                                                                <option value="1" <?php if (isset($_GET['interesse']) and $_GET['interesse'] == 1) echo "selected='selected'" ?>>Oui</option>
                                                                <option value="2" <?php if (isset($_GET['interesse']) and $_GET['interesse'] == 2) echo "selected='selected'" ?>>Non</option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="secteur" value="<?php if (isset($id_secteur)) echo $id_secteur ?>">
                                                        <input type="hidden" name="type" value="<?php if (isset($id_type)) echo $id_type ?>">
                                                        <input type="hidden" name="origine" value="<?php if (isset($id_origine)) echo $id_origine ?>">
                                                        <?php
                                                        if (isset($_GET['departement']) and $_GET['departement'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="departement" value="0">
                                                                <span aria-hidden="true"><?php echo $get_departement ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['ville']) and $_GET['ville'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="ville" value="0">
                                                                <span aria-hidden="true"><?php echo $id_ville ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (isset($_GET['secteur']) and $_GET['secteur'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="secteur" value="0">
                                                                <span aria-hidden="true"><?php echo $id_secteur_nom ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (isset($_GET['type']) and $_GET['type'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="type" value="0">
                                                                <span aria-hidden="true"><?php echo $id_type_nom ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (isset($_GET['origine']) and $_GET['origine'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="origine" value="0">
                                                                <span aria-hidden="true"><?php echo $id_origine_nom ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (isset($_GET['interesse']) and $_GET['interesse'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="interesse" value="0">
                                                                <span aria-hidden="true"><?php echo $get_interesse ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </form>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_etab_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM etablissements WHERE etab_id = etab_id " . $sql_departement . $sql_ville . $sql_secteur . $sql_type . $sql_origine . $sql_interesse, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_etab_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM etablissements WHERE etab_id = etab_id AND etab_raisonSocial LIKE '%$search%' " . $sql_departement . $sql_ville . $sql_secteur . $sql_type . $sql_origine . $sql_interesse, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_etab_count) {
                                                ?>
                                                        <tr>
                                                            <th>Raison sociale</th>
                                                            <th>Département</th>
                                                            <th>Ville</th>
                                                            <th>Tél</th>
                                                            <th>Mail</th>
                                                            <th>Intéressé</th>
                                                            <th>Alternant(s)</th>
                                                            <th>Demande(s)</th>
                                                            <th>Offre(s)</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_etab_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_etab_count établissement $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_etab_count établissements $msg_search</h1>";
                                                        }
                                                        $sql_etab = "SELECT *," . $sql_trie_select . " FROM etablissements WHERE etab_ville = etab_ville " . $sql_departement . $sql_ville . $sql_secteur . $sql_type . $sql_origine  . $sql_interesse . $sql_trie_order . $ordre . ", (etab_raisonSocial) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = $_GET['search'];
                                                            $sql_etab = "SELECT *," . $sql_trie_select . " FROM etablissements WHERE etab_ville = etab_ville AND etab_raisonSocial LIKE '%$search%' " . $sql_departement . $sql_ville . $sql_secteur . $sql_type . $sql_origine  . $sql_interesse . $sql_trie_order . $ordre . ", (etab_raisonSocial) ASC LIMIT $limit";
                                                        }
                                                        $lesUsers = lireLesUsers($pdo, $sql_etab);
                                                        foreach ($lesUsers as $unUser) {
                                                            $id = $unUser['etab_id'];
                                                            $nom = $unUser['etab_raisonSocial'];
                                                            $secteur = $unUser['etab_idSecteur'];
                                                            $secteur = requeteSQL($pdo, "SELECT * FROM secteurs_entreprise WHERE secteur_id = '$secteur'", "secteur_nom");
                                                            $type = $unUser['etab_idType'];
                                                            $type = requeteSQL($pdo, "SELECT * FROM types_entreprise WHERE typeEnt_id = '$type'", "typeEnt_intitule");
                                                            $origine = $unUser['etab_idOrigine'];
                                                            $origine = requeteSQL($pdo, "SELECT * FROM origines_entreprise WHERE origineEnt_id = '$origine'", "origineEnt_intitule");
                                                            $departement = $unUser['etab_idDepartement'];
                                                            $departement = requeteSQL($pdo, "SELECT * FROM departement WHERE departement_id = '$departement'", "departement_code");
                                                            $departement = $departement . " - " . requeteSQL($pdo, "SELECT * FROM departement WHERE departement_code = '$departement'", "departement_nom");
                                                            $adresse = $unUser['etab_adresse'];
                                                            $ville = $unUser['etab_ville'];
                                                            $cp = $unUser['etab_cp'];
                                                            $tel = $unUser['etab_tel'];
                                                            $tel = wordwrap("$tel", 2, ' ', true);
                                                            $mail = $unUser['etab_mail'];
                                                            $taxe = $unUser['etab_donnateur'];
                                                            if ($taxe) {
                                                                $taxe = "Oui";
                                                            } else {
                                                                $taxe = "Non";
                                                            }
                                                            $eleves = requeteSQl($pdo, "SELECT COUNT(*) as eleves FROM alternances WHERE alt_idEtab = '$id' AND alt_actif = 1 AND '$date' <= alt_fin", "eleves");
                                                            $demandes = requeteSQl($pdo, "SELECT COUNT(*) as demandes FROM offres_alternances WHERE offreAlt_idEtab = '$id' AND offreAlt_demande = 1 AND offreAlt_idAlt = 0", "demandes");
                                                            $offres = requeteSQl($pdo, "SELECT COUNT(*) as offres FROM offres_alternances WHERE offreAlt_idEtab = '$id' AND offreAlt_demande = 0 AND offreAlt_idAlt = 0", "offres");

                                                            $interesse = $unUser['etab_interest'];
                                                            if ($interesse) {
                                                                $interesse = "Oui";
                                                                $class_td = "";
                                                            } else {
                                                                $interesse = "Non";
                                                                $class_td = "table-danger";
                                                            }
                                                            echo ("<tr><td><a href='fiche_etablissement?id=$id'>$nom</a></td>
                                            <td>$departement</td>
                                            <td>$ville</td>
                                            <td>$tel</td>
                                            <td><a href='mailto:$mail'>$mail</a></td>
                                            <td class=$class_td>$interesse</td>
                                            <td>$eleves</td>
                                            <td>$demandes</td>
                                            <td>$offres</td></tr>");
                                                        }
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>Aucun établissement $msg_search</h1>";
                                                    }
                                                } catch (Exception $e) {
                                                    die("Ereur grave : " . $e->getMessage());
                                                }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['sites'])) echo 'show active' ?>" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <div class="">
                                        <table class=" table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_site.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter un site secondaire</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="sites" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="type_site" value="<?php if (isset($id_type_site)) echo $id_type_site ?>">

                                                        <div class="input-group mb-3">
                                                            <input list="etablissements" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un établissement..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                            <datalist id="etablissements">
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
                                                        <input type="hidden" name="sites" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="type_site" value="<?php if (isset($id_type_site)) echo $id_type_site ?>">
                                                        <?php if (!empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                            </div>
                                                        <?php } ?>
                                                    </form>
                                                </div>

                                                <div>
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="sites" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="type_site" value="<?php if (isset($id_type_site)) echo $id_type_site ?>">

                                                        <select class="form-control select_etab select_etab_gauche" name="departement" onchange="this.form.submit()">
                                                            <option value="0">Choisir un département</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM departement") as $unDepart) {
                                                                $iddepart = $unDepart['departement_id'];
                                                                $codeDepart = $unDepart['departement_code'];
                                                                $nomDepart = $unDepart['departement_nom'];
                                                                if (isset($_GET['departement']) and $iddepart == $_GET['departement']) {
                                                                    echo ("<option value='$iddepart' selected='selected'>$codeDepart - $nomDepart</option>");
                                                                } else {
                                                                    echo ("<option value='$iddepart'>$codeDepart - $nomDepart</option>");
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <select class="form-control select_etab" name="ville" onchange="this.form.submit()">
                                                            <option value="0">Choisir une ville</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM etablissements WHERE etab_ville <> '' GROUP BY etab_ville ORDER BY etab_ville") as $unDepart) {
                                                                $etab_ville = $unDepart['etab_ville'];
                                                                if (isset($_GET['ville']) and $etab_ville == $_GET['ville']) {
                                                                    echo ("<option selected='selected'>$etab_ville</option>");
                                                                } else {
                                                                    echo ("<option>$etab_ville</option>");
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <select class="form-control select_etab" name="type_site" onchange="this.form.submit()">
                                                            <option value="0">Choisir un type de site</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM types_sites") as $unType) {
                                                                $idtype = $unType['typeSite_id'];
                                                                $nomtype = $unType['typeSite_intitule'];
                                                                if (isset($_GET['type_site']) and $_GET['type_site'] == $idtype) {
                                                                    echo ("<option value='$idtype' selected='selected'>$nomtype</option>");
                                                                } else {
                                                                    echo ("<option value='$idtype'>$nomtype</option>");
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="sites" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="ville" value="<?php if (isset($id_ville)) echo $id_ville ?>">
                                                        <input type="hidden" name="type_site" value="<?php if (isset($id_type_site)) echo $id_type_site ?>">
                                                        <?php
                                                        if (isset($_GET['departement']) and $_GET['departement'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="departement" value="0">
                                                                <span aria-hidden="true"><?php echo $get_departement ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        } ?>

                                                        <?php
                                                        if (isset($_GET['ville']) and $_GET['ville'] != '0') {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="ville" value="0">
                                                                <span aria-hidden="true"><?php echo $id_ville ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>

                                                        <?php
                                                        if (isset($_GET['type_site']) and $_GET['type_site'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="type_site" value="0">
                                                                <span aria-hidden="true"><?php echo $id_type_site_nom ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </form>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_site_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM sites_entreprise WHERE site_id != '-1' AND site_id != '0' " . $sql_departement2 . $sql_ville2 . $sql_type_site, "total");
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_site_count = requeteSQL($pdo, "SELECT COUNT(*) as total FROM sites_entreprise, etablissements WHERE site_id != '-1' AND site_id != '0' AND site_idEtab = etab_id AND etab_raisonSocial LIKE '%$search%' " . $sql_departement2 . $sql_ville2 . $sql_type_site, "total");
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_site_count) {
                                                ?>
                                                        <tr>
                                                            <th>Établissement</th>
                                                            <th>Type de site</th>
                                                            <th>Département</th>
                                                            <th>Ville</th>
                                                            <th>Tél</th>
                                                            <th>Mail</th>
                                                            <th>Alternant(s)</th>
                                                            <th>Demande(s)</th>
                                                            <th>Offre(s)</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_site_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_site_count site secondaire $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_site_count sites secondaires $msg_search</h1>";
                                                        }

                                                        $sql_site = "SELECT *," . $sql_trie_select2 . " FROM sites_entreprise, etablissements WHERE etab_id = site_idEtab " . $sql_departement2 . $sql_ville2 . $sql_type_site . $sql_trie_order2 . $ordre . ", (etab_raisonSocial) ASC LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = $_GET['search'];
                                                            $sql_site = "SELECT *," . $sql_trie_select2 . " FROM sites_entreprise, etablissements WHERE etab_id = site_idEtab AND etab_raisonSocial LIKE '%$search%' " . $sql_departement2 . $sql_ville2 . $sql_type_site . $sql_trie_order2 . $ordre . ", (etab_raisonSocial) ASC LIMIT $limit";
                                                        }

                                                        $lesUsers = lireLesUsers($pdo, $sql_site);
                                                        foreach ($lesUsers as $unUser) {
                                                            $id = $unUser['site_idEtab'];
                                                            $nom = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $id", "etab_raisonSocial");

                                                            $id_site = $unUser['site_id'];
                                                            $id_site_type = $unUser['site_idTypeSite'];
                                                            $id_site_type = requeteSQL($pdo, "SELECT * FROM types_sites WHERE typeSite_id = $id_site_type", "typeSite_intitule");
                                                            $id_site_departement = $unUser['site_idDepartement'];
                                                            $departement = requeteSQL($pdo, "SELECT * FROM departement WHERE departement_id = '$id_site_departement'", "departement_code");
                                                            $departement = $departement . " - " . requeteSQL($pdo, "SELECT * FROM departement WHERE departement_code = '$departement'", "departement_nom");
                                                            $id_site_adresse = $unUser['site_adresse'];
                                                            $id_site_ville = $unUser['site_ville'];
                                                            $id_site_cp = $unUser['site_cp'];
                                                            $id_site_tel = $unUser['site_tel'];
                                                            $tel = wordwrap("$id_site_tel", 2, ' ', true);
                                                            $id_site_mail = $unUser['site_mail'];
                                                            $eleves = requeteSQl($pdo, "SELECT COUNT(*) as eleves FROM alternances WHERE alt_idSite = '$id_site' AND alt_actif = 1", "eleves");
                                                            $demandes = requeteSQl($pdo, "SELECT COUNT(*) as demandes FROM offres_alternances WHERE offreAlt_idEtab = '$id' AND offreAlt_demande = 1 AND offreAlt_idAlt = 0 AND offreAlt_idSite = $id_site", "demandes");
                                                            $offres = requeteSQl($pdo, "SELECT COUNT(*) as offres FROM offres_alternances WHERE offreAlt_idEtab = '$id' AND offreAlt_demande = 0 AND offreAlt_idAlt = 0 AND offreAlt_idSite = $id_site", "offres");

                                                            echo ("<tr><td><a href='fiche_etablissement?id=$id'>$nom</a></td>
                                            <td><a href='fiche_site?id=$id_site'>$id_site_type</a></td>
                                            <td>$departement</td>
                                            <td>$id_site_ville</td>
                                            <td>$id_site_tel</td>
                                            <td><a href='mailto:$id_site_mail'>$id_site_mail</a></td>
                                            <td>$eleves</td>
                                            <td>$demandes</td>
                                            <td>$offres</td></tr>");
                                                        }
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>Aucun site secondaire $msg_search</h1>";
                                                    }
                                                } catch (Exception $e) {
                                                    die("Ereur grave : " . $e->getMessage());
                                                }
                                        ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="text-nowrap tab-pane fade <?php if (!empty($_GET['collabes'])) echo 'show active' ?>" id="nav-profile2" role="tabpanel" aria-labelledby="nav-profile-tab2">
                                    <?php
                                    if (isset($_GET['formationCfa']) and $_GET['formationCfa'] != 0) {
                                        $id_formationCfa = $_GET['formationCfa'];
                                        $sql_formationCfa = " AND participationCollab_idFormationCfa = '$id_formationCfa'";
                                        $get_formationCfa = requeteSQL($pdo, "SELECT * FROM formation_cfa WHERE formationCfa_id = $id_formationCfa", "formationCfa_intitule");
                                    } else {
                                        $id_formationCfa = 0;
                                        $sql_formationCfa = "";
                                    }
                                    ?>
                                    <div class="">
                                        <table class="table table-striped sortable" cellspacing="0">
                                            <thead>
                                                <div>
                                                    <form action="ajouter_collab.php">
                                                        <button type="submit" class="btn btn-primary button_w100 margin_30">Ajouter une collaboration CFA</button>
                                                    </form>
                                                </div>

                                                <div class="margin_bottom20">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="collabes" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="formationCfa" value="<?php if (isset($id_formationCfa)) echo $id_formationCfa ?>">

                                                        <div class="input-group mb-3">
                                                            <input list="etablissements" aria-describedby="search" class="form-control button_w100" id="search" name="search" placeholder="Chercher un établissement..." value="<?php if (isset($_GET['search']) and !empty($_GET['search'])) echo $_GET['search']; ?>" />
                                                            <datalist id="etablissements">
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
                                                        <input type="hidden" name="collabes" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="formationCfa" value="<?php if (isset($id_formationCfa)) echo $id_formationCfa ?>">

                                                        <?php if (!empty($_GET['search'])) { ?>
                                                            <div class="input-group-prepend">
                                                                <button type="submit" class="input-group-text cursor" id="search" name="search" value="">&times;</button>
                                                            </div>
                                                        <?php } ?>
                                                    </form>
                                                </div>

                                                <div>
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="collabes" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="formationCfa" value="<?php if (isset($id_formationCfa)) echo $id_formationCfa ?>">



                                                        <select class="form-control select_etab select_etab_gauche" name="formationCfa" onchange="this.form.submit()">
                                                            <option value="0">Choisir une formation</option>
                                                            <?php
                                                            foreach (lireLesUsers($pdo, "SELECT * FROM formation_cfa WHERE formationCfa_id != 0") as $uneForm) {
                                                                $id_form = $uneForm['formationCfa_id'];
                                                                $nom_form = $uneForm['formationCfa_intitule'];
                                                                if ($id_formationCfa == $id_form) {
                                                                    echo ("<option value='$id_form' selected='selected'>$nom_form</option>");
                                                                } else {
                                                                    echo ("<option value='$id_form'>$nom_form</option>");;
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </form>
                                                </div>

                                                <div class="titre_tableau">
                                                    <form method="GET" enctype="multipart/form-data">
                                                        <input type="hidden" name="collabes" value="1">
                                                        <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search'] ?>"> <input type="hidden" name="departement" value="<?php if (isset($id_departement)) echo $id_departement ?>">
                                                        <input type="hidden" name="formationCfa" value="<?php if (isset($id_formationCfa)) echo $id_formationCfa ?>">

                                                        <?php
                                                        if (isset($_GET['formationCfa']) and $_GET['formationCfa'] != 0) {
                                                        ?>
                                                            <button type="submit" class="close etab_criteres" aria-label="Close" name="formationCfa" value="0">
                                                                <span aria-hidden="true"><?php echo $get_formationCfa ?> &times;</span>
                                                            </button>
                                                        <?php
                                                        }
                                                        ?>
                                                    </form>
                                                </div>

                                                <?php
                                                try {
                                                    $sql_collab_count = $pdo->query("SELECT * FROM collab_cfa, etablissements, participation_collab, formation_cfa WHERE participationCollab_idCollab = collab_id AND participationCollab_idFormationCfa = formationCfa_id AND (collab_idCfa = etab_id OR collab_idEtab = etab_id) " . $sql_formationCfa  . " GROUP BY collab_id")->rowCount();
                                                    if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                        $search = htmlentities($_GET['search']);
                                                        $sql_collab_count = $pdo->query("SELECT * FROM collab_cfa, etablissements, participation_collab, formation_cfa WHERE participationCollab_idCollab = collab_id AND participationCollab_idFormationCfa = formationCfa_id AND (collab_idCfa = etab_id OR collab_idEtab = etab_id) AND (etab_raisonSocial LIKE '%$search%') " . $sql_formationCfa  . " GROUP BY collab_id")->rowCount();
                                                        $msg_search = " pour '<b>$search</b>'";
                                                    } else {
                                                        $msg_search = "";
                                                    }
                                                    if ($sql_collab_count) {
                                                ?>
                                                        <tr>
                                                            <th>Établissement</th>
                                                            <th>CFA</th>
                                                            <th>Formation(s)</th>
                                                            <th>Fiche</th>
                                                        </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                                        if ($sql_collab_count == 1) {
                                                            echo "<h1 class='titre_tableau'>$sql_collab_count collaboration CFA $msg_search</h1>";
                                                        } else {
                                                            echo "<h1 class='titre_tableau'>$sql_collab_count collaborations CFA $msg_search</h1>";
                                                        }

                                                        $sql_collab = "SELECT * FROM collab_cfa, etablissements, participation_collab, formation_cfa WHERE participationCollab_idCollab = collab_id AND participationCollab_idFormationCfa = formationCfa_id AND (collab_idCfa = etab_id OR collab_idEtab = etab_id) " . $sql_formationCfa  . " GROUP BY collab_id  ORDER BY etab_raisonSocial $ordre LIMIT $limit";
                                                        if (isset($_GET['search']) and !empty($_GET['search'])) {
                                                            $search = $_GET['search'];
                                                            $sql_collab = "SELECT * FROM collab_cfa, etablissements, participation_collab, formation_cfa WHERE participationCollab_idCollab = collab_id AND participationCollab_idFormationCfa = formationCfa_id AND (collab_idCfa = etab_id OR collab_idEtab = etab_id) AND (etab_raisonSocial LIKE '%$search%') " . $sql_formationCfa  . "  GROUP BY collab_id ORDER BY etab_raisonSocial $ordre LIMIT $limit";
                                                        }

                                                        $lesUsers = lireLesUsers($pdo, $sql_collab);
                                                        foreach ($lesUsers as $unUser) {
                                                            $id_collab = $unUser['collab_id'];
                                                            $id_etab = $unUser['collab_idEtab'];
                                                            $id_cfa = $unUser['collab_idCFA'];
                                                            $etab_rs = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $id_etab", "etab_raisonSocial");
                                                            $etab_cfa = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $id_cfa", "etab_raisonSocial");


                                                            $list_formationCfa = "";
                                                            $lesFormationCfa = lireLesUsers($pdo, "SELECT * FROM participation_collab, formation_cfa WHERE participationCollab_idFormationCfa = formationCfa_id AND participationCollab_idCollab = $id_collab");
                                                            foreach ($lesFormationCfa as $uneFormationCfa) {
                                                                $formationCfa_id = $uneFormationCfa['formationCfa_id'];
                                                                $formationCfa_intitule = $uneFormationCfa['formationCfa_intitule'];
                                                                if (empty($list_formationCfa)) {
                                                                    $list_formationCfa = $formationCfa_intitule;
                                                                } else {
                                                                    $list_formationCfa = $list_formationCfa . "<br>" . $formationCfa_intitule;
                                                                }
                                                            }

                                                            echo ("<tr><td><a href='fiche_etablissement?id=$id_etab'>$etab_rs</a></td>
                                                <td><a href='fiche_etablissement?id=$id_cfa'>$etab_cfa</a></td>
                                                <td>$list_formationCfa</td>
                                                <td><a href='fiche_collab?id=$id_collab'>Fiche</a></td>
                                                </tr>");
                                                        }
                                                    } else {
                                                        echo "<h1 class='titre_tableau'>Aucune collaboration $msg_search</h1>";
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