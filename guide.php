<!DOCTYPE html>
<html lang="en">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
?>

<head>
    <title>MFR BDD - Guide d'utilisation</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 mx-auto">
                <div class="jumbotron">
                    <h1>MFR BDD - GUIDE D'UTILISATION</h1>
                    <p>MFR BDD est un outil qui permet de contacter, prospecter et suivre les entreprises partenaires du CFA.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10 mx-auto">
                <h2>Fonctionnalités : </h2>
                <ul class="list-group">
                    <li class="list-group-item">Répertorier tous les établissements donateurs de taxe d’apprentissage</li>
                    <li class="list-group-item">Répertorier les établissements partenaires qui ont eu des élèves MFR</li>
                    <li class="list-group-item">Répertorier les contacts : différencier les décideurs et les tuteurs (et autres)</li>
                    <li class="list-group-item">Répertorier les sites d’accueil parfois différents des sites administratifs</li>
                    <li class="list-group-item">Savoir quelles formations intéressent quels établissements</li>
                    <li class="list-group-item">Savoir si les entreprises travaillent avec d’autres CFA, lesquels et quelles formations</li>
                    <li class="list-group-item">Conserver une trace de chaque contact avec l’entreprise, l’interlocuteur, le contenu de l’entretien</li>
                    <li class="list-group-item">Pouvoir consulter l’historique de ces contacts</li>
                    <li class="list-group-item">Pouvoir intégrer un évènement particulier avec l’entreprise et le consulter</li>
                    <li class="list-group-item">Pouvoir intégrer dans la fiche entreprise une offre d’alternance</li>
                    <li class="list-group-item">Pouvoir faire un suivi de cette offre alternance</li>
                    <li class="list-group-item">Pouvoir récupérer chaque année dans IMFR les étudiants et les injecter dans la BDD</li>
                    <li class="list-group-item">Pouvoir sectoriser les entreprises par activité, département, nombre de salariés</li>
                    <li class="list-group-item">Pouvoir faire un e-mailing</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-10 mx-auto mt-5">
                <h1>Insértion de données</h1>
                <div class="accordion">
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne">
                                    Q: Comment ajouter un établissement ?
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_etablissement?php">ajouter_etablissement</a>, accesible à partir du menu de navigation, onglet <b>'Les établissements'</b> ou la page <a href="liste_etablissements.php">liste_etablisements</a>, onglet <b>'Les établissements'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo">
                                    Q: Comment ajouter un site secondaire ?
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_site?php">ajouter_site</a>, accesible à partir du menu de navigation, onglet <b>'Les établissements'</b> ou la page <a href="liste_etablissements?sites=1">liste_etablissements</a>, onglet <b>'Les sites secondaires'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree">
                                    Q: Comment ajouter une collaboration CFA ?
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_collab?php">ajouter_collab</a>, accesible à partir du menu de navigation, onglet <b>'Les établissements'</b> ou la page <a href="liste_etablissements?collabes=1">liste_etablissements</a>, onglet <b>'Les collaborations CFA'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c4-1">
                                    Q: Comment ajouter un(e) alternant(e) ?
                                </button>
                            </h5>
                        </div>
                        <div id="c4-1" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_alternance.php">ajouter_alternance</a>, accesible à partir du menu de navigation, onglet <b>'Les alternances'</b> ou la page <a href="liste_alternances.php">liste_alternances</a>, onglet <b>'Les alternances</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c4">
                                    Q: Comment ajouter une offre ?
                                </button>
                            </h5>
                        </div>
                        <div id="c4" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_alternance.php">ajouter_alternance</a>, accesible à partir du menu de navigation, onglet <b>'Les alternances'</b> ou la page <a href="liste_alternances?offres=1">liste_alternances</a>, onglet <b>'Les offres'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c5">
                                    Q: Comment ajouter une demande ?
                                </button>
                            </h5>
                        </div>
                        <div id="c5" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_alternance.php">ajouter_alternance</a>, accesible à partir du menu de navigation, onglet <b>'Les alternances'</b> ou la page <a href="liste_alternances?demandes=1">liste_alternances</a>, onglet <b>'Les demandes'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c6">
                                    Q: Comment ajouter un contact ?
                                </button>
                            </h5>
                        </div>
                        <div id="c6" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_contact.php">ajouter_contact</a>, accesible à partir du menu de navigation, onglet <b>'Les contacts'</b> ou la page <a href="liste_contacts.php">liste_contacts</a>, onglet <b>'Les contacts'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c7">
                                    Q: Comment ajouter un entretien ?
                                </button>
                            </h5>
                        </div>
                        <div id="c7" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_contact.php">ajouter_contact</a>, accesible à partir du menu de navigation, onglet <b>'Les contacts'</b> ou la page <a href="liste_contacts?entretiens=1">liste_contacts</a>, onglet <b>'Les entretiens'</b>.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c7-2">
                                    Q: Comment ajouter un évènement ?
                                </button>
                            </h5>
                        </div>
                        <div id="c7-2" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la page <a href="ajouter_evenement.php">ajouter_evenement</a>, accesible à partir du menu de navigation, onglet <b>'Les évènement'</b> ou la page <a href="liste_evenements.php">liste_evenements</a>, onglet <b>'Les évènements'</b>.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-10 mx-auto mt-5">
                <h1>Modification et suppression de données</h1>
                <div class="accordion">
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#c8">
                                    Q: Comment modifier ou supprimer un établissement ?
                                </button>
                            </h5>
                        </div>

                        <div id="c8" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier un établissement, il faut se rendre sur sa fiche, rubrique principal, nommé par la raison sociale de l'établissement, remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un établissement, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.
                                <br><br>La fiche d'un établissement est accesible entre autre à partir de la page <a href="liste_etablissements.php">liste_etablissements</a>, rubrique <b>'Les établissement'</b>en cliquant sur la raison social de l'établissement ou sur 'Fiche' à droite du tableau.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c9">
                                    Q: Comment modifier ou supprimer un site secondaire ?
                                </button>
                            </h5>
                        </div>
                        <div id="c9" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier un site secondaire, il faut se rendre sur sa fiche, rubrique principal, nommé par le type du site, remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un site secondaire, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.
                                <br><br>La fiche d'un site secondaire est accesible entre autre à partir de la page <a href="liste_etablissements?site=1">liste_etablissements</a>, rubrique <b>'Les sites secondaires'</b>, en cliquant sur le type du site ou sur 'Fiche' à droite du tableau.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c10">
                                    Q: Comment modifier ou supprimer une collaboration CFA ?
                                </button>
                            </h5>
                        </div>
                        <div id="c10" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier une collaboration CFA, il faut se rendre sur sa fiche remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> une collaboration CFA, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'une collaboration CFA est accesible entre autre à partir de la page <a href="liste_etablissements?collabes=1">liste_etablissements</a>, rubrique <b>'Les collaborations CFA'</b>, en cliquant sur 'Fiche' à droite du tableau.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c112">
                                    Q: Comment modifier ou supprimer une alternance ?
                                </button>
                            </h5>
                        </div>
                        <div id="c112" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier une alternance, il faut se rendre sur sa fiche remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un contrat d'alternance, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'un contrat d'alternance est accesible entre autre à partir de la page <a href="liste_alternances?">liste_alternances</a>, rubrique <b>'Les alternances'</b>, en cliquant sur le nom de l'alternant(e) ou sur 'Fiche' à droite du tableau.
                                <br><br>Une fiche de contrat d'alternance à 6 états : <b>'Pas commencée'</b>, <b>'1ère année'</b>, <b>'2ème année'</b>, <b>'3ème année'</b>, <b>'Finie'</b> et <b>'Rupture de contrat'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c11">
                                    Q: Comment modifier ou supprimer une offre ?
                                </button>
                            </h5>
                        </div>
                        <div id="c11" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier une offre, il faut se rendre sur sa fiche remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> une offre, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'une offre d'alternances est accesible entre autre à partir de la page <a href="liste_alternances?offres=1?">liste_alternances</a>, rubrique <b>'Les offres'</b>, en cliquant sur 'Fiche' à droite du tableau.
                                <br><br>Une offre d'alternance à 3 états : <b>'En attente'</b>, <b>'Refusée'</b> et <b>'Acceptée'</b>. Afin de déterminer les offres pour lesquelles un CV a été envoyé, il suffit de remplir un nom ou un prénom dans la fiche d'offre, en laissant l'état de l'offre '<b>En attente'</b> et de confirmer.
                                <br><br>Si une offre est acceptée, l'élève attribué à cette offre sera automatiquement stocké en tant qu'alternant.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c12">
                                    Q: Comment modifier ou supprimer une demande ?
                                </button>
                            </h5>
                        </div>
                        <div id="c12" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier une demande, il faut se rendre sur sa fiche remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> une demande, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'une demande d'alternances est accesible entre autre à partir de la page <a href="liste_alternances?demandes=1?">liste_alternances</a>, rubrique <b>'Les demandes'</b>, en cliquant sur 'Fiche' à droite du tableau.
                                <br><br>Une demande d'alternance à 3 états : <b>'En attente'</b>, <b>'Refusée'</b> et <b>'Acceptée'</b>. Afin de déterminer les demandes pour lesquelles un CV a été envoyé, il suffit de remplir un nom ou un prénom dans la fiche d'offre, en laissant l'état de la demande '<b>En attente'</b> et de confirmer.
                                <br><br>Si une demande est acceptée, l'élève attribué à cette demande sera automatiquement stocké en tant qu'alternant.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c13">
                                    Q: Comment modifier ou supprimer un contact ?
                                </button>
                            </h5>
                        </div>
                        <div id="c13" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier un contact, il faut se rendre sur sa fiche, rubrique principal, nommé par le nom du contact, remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un contact, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'un contact est accesible entre autre à partir de la page <a href="liste_contacts?">liste_contacts</a>, rubrique <b>'Les contacts'</b>, en cliquant sur le nom du contact ou sur 'Fiche' à droite du tableau.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c14">
                                    Q: Comment modifier ou supprimer un entretien ?
                                </button>
                            </h5>
                        </div>
                        <div id="c14" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier un entretien, il faut se rendre sur sa fiche, remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un entretien, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'un entretien est accesible entre autre à partir de la page <a href="liste_contacts?">liste_contacts</a>, rubrique <b>'Les entretiens'</b>, en cliquant sur 'Fiche' à droite du tableau.
                                <br><br><b>Pour rappeler un contact</b>, il faut cliquer sur la colonne <b>'État'</b>, et remplir le nouvel entretien. <br>
                                <b>Attention</b>, si l'ajout d'un nouvel entretien est accédé par d'autres moyens, le contact ne sera pas considéré comme rappelé !
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c142">
                                    Q: Comment modifier ou supprimer un évènement ?
                                </button>
                            </h5>
                        </div>
                        <div id="c142" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Pour modifier un évènement, il faut se rendre sur sa fiche, remplir le formulaire et cliquer sur le bouton 'Modifier' en bas de la page.
                                <br><br>Pour <b>supprimer</b> un évènement, il faut être <b>Super Administrateur</b>, se rendre sur sa fiche, et cliquer sur le bouton </b>'Supprimer'</b> en bas de la page.

                                <br><br>La fiche d'un évènement est accesible entre autre à partir de la page <a href="liste_evenements?">liste_evenements</a>, rubrique <b>'Les évènements'</b>, en cliquant sur 'Fiche' à droite du tableau.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-10 mx-auto mt-5">
                <h1>Ajout & modification de critères</h1>
                <div class="accordion">
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#c151">
                                    Q: Comment modifier le statut d'un membre ?
                                </button>
                            </h5>
                        </div>

                        <div id="c151" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'membres'</b>.
                                <br><br>Une fois sur la table <b>'membres'</b>, il faut double-cliquer sur la colonne, à droite du tableau, <b>'memb_admin'</b> du membre pour lequel on veut changer le statut, et changer la valeur en 0, 1, 2 ou 3.
                                <br><br>Pour rappel ; <br> <b>0 = Visiteur </b>(Aucun droit)<b><br> 1 = Utilisateur </b>(droit de consultation)<b><br> 2 = Administrateur </b>(tous les droits sauf droits de suppression)<b><br> 3 = SuperAdministrateur </b>(tous les droits)<b></b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#c15">
                                    Q: Comment ajouter une formation ?
                                </button>
                            </h5>
                        </div>

                        <div id="c15" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'formations'</b>.
                                <br><br>Une fois sur la table <b>'formations'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'formation_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c16">
                                    Q: Comment ajouter une fonction de contact ?
                                </button>
                            </h5>
                        </div>
                        <div id="c16" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'fonctions_contacts'</b>.
                                <br><br>Une fois sur la table <b>'fonction_contacts'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'fonction_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c17">
                                    Q: Comment ajouter une origine d'établissement ?
                                </button>
                            </h5>
                        </div>
                        <div id="c17" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'origines_entreprise'</b>.
                                <br><br>Une fois sur la table <b>'origines_entreprise'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'origineEnt_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c18">
                                    Q: Comment ajouter un secteur d'activité ?
                                </button>
                            </h5>
                        </div>
                        <div id="c18" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'secteurs_entreprise'</b>.
                                <br><br>Une fois sur la table <b>'secteurs_entreprise'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'secteur_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c19">
                                    Q: Comment ajouter un type d'établissement ?
                                </button>
                            </h5>
                        </div>
                        <div id="c19" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'types_entreprise'</b>.
                                <br><br>Une fois sur la table <b>'types_entreprise'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'typeEnt_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c20">
                                    Q: Comment ajouter un type d'évènement ?
                                </button>
                            </h5>
                        </div>
                        <div id="c20" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'types_evenements'</b>.
                                <br><br>Une fois sur la table <b>'types_evenements'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'typeEvent_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header p-2">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#c21">
                                    Q: Comment ajouter un type de site seconddaire ?
                                </button>
                            </h5>
                        </div>
                        <div id="c21" class="collapse">
                            <div class="card-body">
                                <b>Réponse :</b> Il faut se rendre sur la plateforme <a href="http://192.168.0.60/phpmyadmin/index.php">phpMyAdmin</a>, se connecter avec les identifiants d'administrateur fournis, appuyer sur l'icon <b>'+'</b> à coté de <b>bdd_mfrv5</b>, à <b>gauche</b> de la page, et cliquer sur l'onglet <b>'types_sites'</b>.
                                <br><br>Une fois sur la table <b>'types_sites'</b>, il faut cliquer sur l'onglet <b>'Insérer'</b> en haut de la page, puis remplir le formulaire en laissant <b>'typeSite_id' vide !</b><br><br>
                                Une fois le formulaire rempli, il suffit de cliquer sur le bouton <b>'Exécuter'</b>.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
<?php
require 'footer.php';
?>

</html>