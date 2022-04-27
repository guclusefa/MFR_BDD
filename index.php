<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
?>

<head>
    <title>MFR BDD - Accueil</title>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION['erreur'])) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo "<div class='container'>";
            echo $_SESSION['erreur'];
            echo "</div>";
            echo "</div>";
            unset($_SESSION['erreur']);
        }
        ?>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success' role='alert'>";
            echo "<div class='container'>";
            echo $_SESSION['success'];
            echo "</div>";
            echo "</div>";
            unset($_SESSION['success']);
        }
        ?>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Statistiques <span class="badge badge-secondary">BETA</span></h1>
            <?php if (isset($_SESSION['id']) and $id_admin > 1) { ?><button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-download fa-sm text-white-50"></i> Exporter la BDD</button><?php } ?>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="sauvegarde?etabs"><i class="fas fa-download fa-sm text-grey-50"></i> Établissements</a>
                <a class="dropdown-item" href="sauvegarde?sites"><i class="fas fa-download fa-sm text-grey-50"></i> Sites secondaires</a>
                <a class="dropdown-item" href="sauvegarde?collabes"><i class="fas fa-download fa-sm text-grey-50"></i> Collaborations CFA</a>
                <a class="dropdown-item" href="sauvegarde?alternances"><i class="fas fa-download fa-sm text-grey-50"></i> Alternances</a>
                <a class="dropdown-item" href="sauvegarde?offres"><i class="fas fa-download fa-sm text-grey-50"></i> Offres</a>
                <a class="dropdown-item" href="sauvegarde?demandes"><i class="fas fa-download fa-sm text-grey-50"></i> Demandes</a>
                <a class="dropdown-item" href="sauvegarde?contacts"><i class="fas fa-download fa-sm text-grey-50"></i> Contacts</a>
                <a class="dropdown-item" href="sauvegarde?entretiens"><i class="fas fa-download fa-sm text-grey-50"></i> Entretiens</a>
                <a class="dropdown-item" href="sauvegarde?events"><i class="fas fa-download fa-sm text-grey-50"></i> Évènements</a>
            </div>
        </div>


        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    <a class="text-primary" href="liste_etablissements.php">Établissements
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM etablissements", "total") ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    <a class="text-success" href="liste_alternances.php">Alternants
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances", "total") ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    <a class="text-info" href="liste_contacts.php">Contacts
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM contacts WHERE contact_id != 0", "total") ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-2x text-gray-300"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    <a class="text-warning" href="liste_evenements.php">Évènements
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM evenements", "total") ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <?php if (isset($_SESSION['id']) and $id_admin > 1) { ?>
                <div class="col-xl-3 col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Offres à traiter</h6>
                        </div>
                        <div class="card-body">
                            <p><a class="text-warning" href="liste_alternances?offres=1&situation=7"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 0 AND ((offreAlt_nom IS NULL OR offreAlt_nom = '') OR (offreAlt_prenom IS NULL OR offreAlt_prenom = '')) ", "total"); ?></b> offre(s) en attente &rarr;</a></p>
                            <p><a href="liste_alternances?offres=1&situation=8"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 0 AND offreAlt_nom <> '' AND offreAlt_prenom <> '' ", "total"); ?></b> offre(s) CV envoyé(s) &rarr;</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Demandes à traiter</h6>
                        </div>
                        <div class="card-body">
                            <p><a class="text-warning" href="liste_alternances?demandes=1&situation=7"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 1 AND ((offreAlt_nom IS NULL OR offreAlt_nom = '') OR (offreAlt_prenom IS NULL OR offreAlt_prenom = '')) ", "total"); ?></b> demande(s) en attente &rarr;</a> </p>
                            <p><a href="liste_alternances?demandes=1&situation=8"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 1 AND offreAlt_nom <> '' AND offreAlt_prenom <> '' ", "total"); ?></b> demande(s) CV envoyé(s) &rarr;</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Contacts à rappeler</h6>
                        </div>
                        <div class="card-body">
                            <p><a href="liste_contacts?entretiens=1&rappeler=1&interlocuteur=<?php echo $id ?>"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM entretiens WHERE entretien_idNextEntretien = -1 AND '$dateSQL' <= entretien_dateRappel2 AND entretien_idMembre = $id ", "total"); ?></b> contact(s) à rappeler &rarr;</a> </p>
                            <p><a class="text-danger" href="liste_contacts?entretiens=1&rappeler=2&interlocuteur=<?php echo $id ?>"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM entretiens WHERE entretien_idNextEntretien = -1 AND '$dateSQL' > entretien_dateRappel2 AND entretien_idMembre = $id ", "total"); ?></b> rappel(s) manqué(s) &rarr;</a> </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Évènements à venir</h6>
                        </div>
                        <div class="card-body">
                            <p><a href="liste_evenements?etat=1"><b><?php echo requeteSQL($pdo, "SELECT COUNT(*) as total FROM evenements WHERE '$date' <= event_date ", "total"); ?></b> évènement(s) à venir &rarr;</a> </p>
                            <p> <br></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Les alternants par année scolaire</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas style="height:300px;" id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Les offres d'alternances en <?php echo $date_annee ?></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas style="height:225px;" id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Acceptée
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Refusée
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> En attente
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Les alternants par formation en année scolaire <?php echo $date_annee ?></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas style="height:300px;" id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Les demandes d'alternances en <?php echo $date_annee ?></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas style="height:225px;" id="myPieChart2"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Acceptée
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Refusée
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> En attente
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $width =  requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'", "total");
        $totalrupture = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee BETWEEN year(alt_debut) AND year(alt_fin) AND year(alt_debut) != $date_annee", "total");
        if ($width > 0 and $totalrupture > 0) {
        ?>
            <div class="row">
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Les ruptures de contrats en <?php echo $date_annee ?><b>(<?php echo ($width * 100) / $totalrupture ?>%)</b></h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Bac Pro SN <span class="float-right"><?php echo $r1 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 1", "total"); ?> (<?php echo ($r1 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo ($r1 * 100) / $width ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">BTS SIO <span class="float-right"><?php echo $r2 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 2", "total"); ?> (<?php echo ($r2 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo ($r2 * 100) / $width ?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">AIS <span class="float-right"><?php echo $r3 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 3", "total"); ?> (<?php echo ($r3 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo ($r3 * 100) / $width ?>%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">BAC PRO TMSEC <span class="float-right"><?php echo $r4 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 4", "total"); ?> (<?php echo ($r4 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo ($r4 * 100) / $width ?>%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">BTS MS SEF <span class="float-right"><?php echo $r5 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 5", "total"); ?> (<?php echo ($r5 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo ($r5 * 100) / $width ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">BAC PRO MEI <span class="float-right"><?php echo $r6 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 6", "total"); ?> (<?php echo ($r6 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: <?php echo ($r6 * 100) / $width ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">BTS MS SP <span class="float-right"><?php echo $r7 = requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE alt_actif = 0 AND year(alt_ruptureDate) = $date_annee AND alt_ruptureDate <= '$date_annee-08-31'  AND alt_idFormation = 7", "total"); ?> (<?php echo ($r7 * 100) / $width ?>%)</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo ($r7 * 100) / $width ?>%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>




    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-bar-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <!-- par annee -->
    <script>
        <?php $test = array(
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee-4 BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee_m4 != year(alt_Debut)", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee-3 BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee_m3 != year(alt_Debut)", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee-2 BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee_m2 != year(alt_Debut)", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee-1 BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee_m1 != year(alt_Debut)", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee != year(alt_Debut)", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE $date_annee+1 BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND $date_annee_1 != year(alt_Debut)", "total")
        );
        $testanne = array(
            $date_annee - 4,
            $date_annee - 3,
            $date_annee - 2,
            $date_annee - 1,
            $date_annee,
            $date_annee + 1
        )
        ?>
        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($testanne); ?>,
                datasets: [{
                    label: "Alternants ",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: <?php echo json_encode($test); ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return '' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    </script>
    <!-- offres -->
    <script>
        <?php
        $test2 = array(
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt > 0 AND offreAlt_demande = 0 AND $date_annee = year(offreAlt_debut)", "total")),
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = -1 AND offreAlt_demande = 0 AND $date_annee = year(offreAlt_debut)", "total")),
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 0 AND $date_annee = year(offreAlt_debut)", "total"))
        );
        ?>
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Acceptées", "Refusées", "En attentes"],
                datasets: [{
                    data: <?php echo json_encode($test2); ?>,
                    backgroundColor: ['#1cc00a', '#e74a3b', '#f6c23e'],
                    hoverBackgroundColor: ['#1cc00f', '#e7400b', '#f6c00e'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>
    <!-- par formation -->
    <script>
        <?php
        $test3 = array(
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 1 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 2 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 3 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 4 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 5 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 6 AND year(alt_debut) != $date_annee", "total"),
            requeteSQL($pdo, "SELECT COUNT(*) as total FROM alternances WHERE '$date_annee' BETWEEN year(alt_debut) AND year(alt_fin) AND alt_actif = 1 AND alt_idFormation = 7 AND year(alt_debut) != $date_annee", "total")
        );
        ?>
        // Bar Chart Example
        var ctx = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["BP SN", "BTS SIO", "AIS", "BP TMSEC", "BTS MSSEF", "BP MEI", "BTS MSSP"],
                datasets: [{
                    label: "Alternants ",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: <?php echo json_encode($test3); ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return '' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });
    </script>
    <!-- demandes -->
    <script>
        <?php
        $test4 = array(
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt > 0 AND offreAlt_demande = 1 AND $date_annee = year(offreAlt_debut)", "total")),
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = -1 AND offreAlt_demande = 1 AND $date_annee = year(offreAlt_debut)", "total")),
            (requeteSQL($pdo, "SELECT COUNT(*) as total FROM offres_alternances WHERE offreAlt_idAlt = 0 AND offreAlt_demande = 1 AND $date_annee = year(offreAlt_debut)", "total"))
        );
        ?>
        var ctx = document.getElementById("myPieChart2");
        var myPieChart2 = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Acceptées", "Refusées", "En attentes"],
                datasets: [{
                    data: <?php echo json_encode($test4); ?>,
                    backgroundColor: ['#1cc00a', '#e74a3b', '#f6c23e'],
                    hoverBackgroundColor: ['#1cc00f', '#e7400b', '#f6c00e'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>
</body>
<?php
require 'footer.php';
?>

</html>
