<?php
require 'bdd.php';
require 'mesfonctions.php';
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $requete = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
    $requete->execute(array($id));
    $requete = $requete->fetch();
    $id_admin = $requete['memb_admin'];
}
if (isset($_SESSION['id']) AND $id_admin > 2) {
    $sql = [];
    if (isset($_GET['id_etab'])) { // etab
        $id = $_GET['id_etab'];
        $sql[0] = "DELETE FROM etablissements WHERE etab_id = $id";
        $sql[1] = "DELETE alternances, eleves FROM alternances INNER JOIN eleves ON eleves.eleve_id = alternances.alt_idEleve WHERE alt_idEtab = $id";
        $sql[2] = "DELETE contacts, entretiens FROM contacts INNER JOIN entretiens ON contacts.contact_id = entretiens.entretien_idContact WHERE contact_idEtab  = $id";
        $sql[3] = "DELETE evenements, participation_event FROM evenements INNER JOIN participation_event ON evenements.event_id = participation_event.participation_idEvent WHERE event_idEtab = $id";
        $sql[4] = "DELETE FROM offres_alternances WHERE offreAlt_idEtab = $id";
        $sql[5] = "DELETE FROM sites_entreprise WHERE site_idEtab = $id";
        $sql[6] = "DELETE contacts WHERE contact_idEtab  = $id";
        $sql[7] = "DELETE collab_cfa, participation_collab FROM collab_cfa INNER JOIN participation_collab ON collab_cfa.collab_id = participation_collab.participationCollab_idCollab WHERE (collab_idEtab = $id OR collab_idCFA = $id)";
        $_SESSION['succes'] = "Établissement supprimé avec succès";
        $header = "liste_etablissements.php";
    }

    if (isset($_GET['id_site'])) { // site
        $id = $_GET['id_site'];
        $sql[0] = "DELETE FROM sites_entreprise WHERE site_id = $id";
        $sql[1] = "DELETE alternances, eleves FROM alternances INNER JOIN eleves ON eleves.eleve_id = alternances.alt_idEleve WHERE alt_idSite = $id";
        $sql[2] = "DELETE contacts, entretiens FROM contacts INNER JOIN entretiens ON contacts.contact_id = entretiens.entretien_idContact WHERE contact_idSite  = $id";
        $sql[3] = "DELETE evenements, participation_event FROM evenements INNER JOIN participation_event ON event_id = participation_idEvent WHERE event_idSite = $id";
        $sql[4] = "DELETE FROM offres_alternances WHERE offreAlt_idSite = $id";
        $_SESSION['succes'] = "Site supprimé avec succès";
        $header = "liste_etablissements?sites=1";
    }

    if (isset($_GET['id_alt'])) {
        $id = $_GET['id_alt'];
        $sql[0] = "DELETE alternances, eleves FROM alternances INNER JOIN eleves ON eleves.eleve_id = alternances.alt_idEleve WHERE alt_id = $id";
        $_SESSION['succes'] = "Alternance supprimé avec succès";
        $header = "liste_alternances.php";
    }

    if (isset($_GET['id_offre'])) {
        $id = $_GET['id_offre'];
        $id_offre_alt = $_GET['id_offre_alt'];
        $id_demande = $_GET['id_demande'];
        $sql[0] = "DELETE FROM offres_alternances WHERE offreAlt_id = $id";
        $sql[1] = "DELETE alternances, eleves FROM alternances INNER JOIN eleves ON eleves.eleve_id = alternances.alt_idEleve WHERE alt_id = $id_offre_alt";
        if ($id_demande) {
            $_SESSION['succes'] = "Demande d'alternance supprimé avec succès";
            $header = "liste_alternances?demandes=1";
        } else {
            $_SESSION['succes'] = "Offre d'alternance supprimé avec succès";
            $header = "liste_alternances?offres=1";
        }
    }

    if (isset($_GET['id_contact'])) {
        $id = $_GET['id_contact'];
        $sql[0] = "DELETE alternances, eleves FROM alternances INNER JOIN eleves ON eleves.eleve_id = alternances.alt_idEleve WHERE alt_idContact = $id";
        $sql[1] = "DELETE FROM contacts WHERE contact_id  = $id";
        $sql[2] = "DELETE FROM entretiens WHERE entretien_idContact  = $id";
        $sql[3] = "DELETE evenements, participation_event FROM evenements INNER JOIN participation_event ON event_id = participation_idEvent WHERE participation_idContact = $id";
        $sql[4] = "DELETE FROM offres_alternances WHERE offreAlt_idContact = $id";
        $_SESSION['succes'] = "Contact supprimé avec succès";
        $header = "liste_contacts.php";
    }

    if (isset($_GET['id_entretien'])) {
        $id = $_GET['id_entretien'];
        $sql[0] = "DELETE FROM entretiens WHERE entretien_id  = $id";
        $_SESSION['succes'] = "Entretien supprimé avec succès";
        $header = "liste_contacts?entretiens=1";
    }

    if (isset($_GET['id_event'])) {
        $id = $_GET['id_event'];
        $sql[0] = "DELETE evenements, participation_event FROM evenements INNER JOIN participation_event ON event_id = participation_idEvent WHERE event_id = $id";
        $_SESSION['succes'] = "Évènement supprimé avec succès";
        $header = "liste_evenements.php";
    }

    if (isset($_GET['id_collab'])) {
        $id = $_GET['id_collab'];
        $sql[0] = "DELETE collab_cfa, participation_collab FROM collab_cfa INNER JOIN participation_collab ON collab_id = participationCollab_idCollab WHERE collab_id = $id";
        $_SESSION['succes'] = "Collaboration supprimé avec succès";
        $header = "liste_etablissements?collabes=1";
    }

    if (isset($_GET['id_membre'])) {
        $id = $_GET['id_membre'];
        $sql[0] = "DELETE FROM entretiens WHERE entretien_idMembre  = $id";
        $sql[1] = "DELETE FROM membres WHERE memb_id  = $id";
        $_SESSION['succes'] = "Membre supprimé avec succès";
        $header = "index.php";
    }

    for ($i = 0; $i < count($sql); $i++) {
        $requete = $pdo->prepare($sql[$i]);
        $requete->execute();
    }

    header("location: $header");
    exit();
} else {
    $_SESSION['erreur'] = "Vous n'avez pas les droits !";
    header("location: index.php");
    exit();
}

