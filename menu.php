<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/mfr_icon.ico">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/bootstrap-sortable.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery.js"></script>
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script type="module" src="js/index.js"></script>

    <link rel="stylesheet" href="css/select2.css" />
    <link rel="stylesheet" href="css/select2-bootstrap4.css">
</head>

<?php
ob_start();
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $requete = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
    $requete->execute(array($id));
    $requete = $requete->fetch();
    $id_admin = $requete['memb_admin'];
?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <nav class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/mfr_logo2.png" height="30" class="d-inline-block align-top" alt="">
                        MFR BDD
                    </a>
                </nav>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="" onclick="window.location.href='profil.php';" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ($requete['memb_prenom'] . " " . $requete['memb_nom']) ?></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profil.php">Mon profil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="liste_etablissements.php" onclick="window.location.href='liste_etablissements.php';">
                                Les établissements 
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="liste_etablissements.php">Les établissements</a>
                                <a class="dropdown-item" href="liste_etablissements.php?sites=1">Les sites secondaires</a>
                                <a class="dropdown-item" href="liste_etablissements.php?collabes=1">Les collaborations CFA</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="ajouter_etablissement.php">Ajouter un établissement</a>
                                <a class="dropdown-item" href="ajouter_site.php">Ajouter un site secondaire</a>
                                <a class="dropdown-item" href="ajouter_collab.php">Ajouter une collaboration CFA</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="" onclick="window.location.href='liste_alternances.php';" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Les alternances
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="liste_alternances.php">Les alternances</a>
                                <a class="dropdown-item" href="liste_alternances.php?offres=1">Les offres</a>
                                <a class="dropdown-item" href="liste_alternances.php?demandes=1">Les demandes</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="ajouter_alternance.php">Ajouter un(e) alternant(e)</a>
                                <a class="dropdown-item" href="ajouter_offre.php">Ajouter une offre</a>
                                <a class="dropdown-item" href="ajouter_offre?id_demande=1.php">Ajouter une demande</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href=""onclick="window.location.href='liste_contacts.php';" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Les contacts
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="liste_contacts.php">Les contacts</a>
                                <a class="dropdown-item" href="liste_contacts.php?entretiens=1">Les entretiens</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="ajouter_contact.php">Ajouter un contact</a>
                                <a class="dropdown-item" href="ajouter_entretien.php">Ajouter un entretien</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="" onclick="window.location.href='liste_evenements.php';" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Les évènements
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="liste_evenements.php">Les évènements</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="ajouter_evenement.php">Ajouter un événement</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="emailing.php">
                                Emailing
                            </a>
                        </li>
                    </ul>
                    <form method="GET" action="chercher.php" class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Chercher..." aria-label="Search" name="search">
                        <button class="btn btn-outline-primary my-2 my-sm-0 btn btn-primary" type="submit">Chercher</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
<?php
} else {
?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <nav class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="index.php">
                        <img src="images/mfr_logo2.png" height="30" class="d-inline-block align-top" alt="">
                        BDD MFR
                    </a>
                </nav>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" onclick="window.location.href='connexion.php';" href="connexion.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Connexion
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="connexion.php">Connexion</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="connexion.php">Inscription</a>
                            </div>
                        </li>
                </div>
            </div>
        </nav>
    </header>
<?php
}
