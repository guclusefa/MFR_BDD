<head>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js">
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
            });
        });
    </script>
</head>

<div class="table-responsive text-nowrap tab-pane fade <?php if (empty($_GET['sites']) and empty($_GET['collabes'])) echo 'show active' ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

    <table id="example" class="table table-bordered" style="width:100%">
        <thead>
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
            $sql_etab = "SELECT * FROM etablissements";
            require 'bdd.php';
            require 'mesfonctions.php';
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
                echo ("
            <tr>
            <td><a href='fiche_etablissement?id=$id'>$nom</a></td>
            <td>$departement</td>
            <td>$ville</td>
            <td>$tel</td>
            <td><a href='mailto:$mail'>$mail</a></td>
                                            <td class=$class_td>$interesse</td>
                                            <td>$eleves</td>
                                            <td>$demandes</td>
                                            <td>$offres</td>
                                            </tr>");
            }

            ?>
        </tbody>
    </table>
</div>
